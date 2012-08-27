AssetManagementBundle [![Build Status](https://secure.travis-ci.org/smoya/AssetManagementBundle.png)](http://travis-ci.org/smoya/AssetManagementBundle)
=====================

An Asset Management Bundle for Symfony2

##TODO:
* Improve README Doc
* More functions
* Tests
* etc

This bundle provides an easy way for
manage assetic packages inclusion in Twig Templates. Of course, We need to have previously installed the [Assetic library ](/kriswallsmith/assetic).

This bundle allows you to print the code that includes assets (javascript and css) there in the place where desired. For example, after loading javascript jquery libraries already loaded at the end of the html code.

In example, this is posible:

Template 1 (Not extends from a base template):
``` jinja
{{ assets_add('assetic/foo.js', 'js') }}
```

Base Template:
``` jinja
{{ assets_render('js') }}
```

Getting as a result:

``` html
<script src="/assetic/foo.js" />
```

## Installation

### Add this entry to the `deps` file

```
[SmoyaAssetManagementBundle]
    git=https://github.com/smoya/AssetManagementBundle.git
    target=/bundles/Smoya/Bundle/AssetManagementBundle
```
    
### Register the bundle into your application Kernel

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            //..
            new Smoya\AssetManagementBundle\SmoyaAssetManagementBundle(),
            //..
        );
    }

Now update vendors:

``` bash
$ ./bin/vendors
```

Now, we need to add this entry to the autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Smoya'        => __DIR__.'/../vendor/bundles',
));
```

## Use

First of all, we need to **set the packages** need to include.
The guys of **Sonata** have a post with an example [here](http://sonata-project.org/blog/2012/5/15/assetic-package-configuration).

Imagine the following case:

###We have 3 Templates
* ::base.html.twig
``` jinja
<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
            {% stylesheets filter='cssrewrite' 'css/compiled/main.css' %}
                <link href="{{ asset_url }}" media="all" type="text/css" rel="stylesheet" />
            {% endstylesheets %}
    </head>
    <body>
        {% block content %}
            This page exists for test SmoyaAssetManagementBundle
        {% endblock %}
    
        {% block javascripts %}
            <script src="{{ asset('assetic/main.js') }}"></script>
        {% endblock %}
    
        {% block extra %}
    
        {% endblock %}
   </body>
</html>
```

* index.html.twig
``` html
    {% extends '::base.html.twig' %}
    {% block content %}
            This page extends from '::base.html.twig' template and i can include code.
        
    {# We need to render a widget #}
    {% render AcmeTestBundle:Test:widget %}
        
    {% endblock %}

    {% block extra %}
        <script src="{{ asset('assetic/bar.js' }}" />
    {% endblock %}
```

* widget.html.twig
``` jinja
    {% block widget %}
        I am a widget and I need render Javascript at the bottom of the website code       
    {% endblock %}
    
    {# This block extra is not the ::base.html.twig 'extra' block #}
    {# Because im not extending the ::base.html.twig template #}
    {% block extra %}
        <script src="{{ asset('assetic/another.js' }}" />
    {% endblock %}
```

###The problem and a solution:
When using **Twig render**, and if the rendered template contains javascript, it will print where the've called. This **is a problem** if you are rendering **before** loading javascripts, especially if the code requires other libraries (eg jQuery).

For this we use the features of Twig adding this bundle as follows:

* ::base.html.twig
``` jinja
<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
            {% stylesheets filter='cssrewrite' 'css/compiled/main.css' %}
                <link href="{{ asset_url }}" media="all" type="text/css" rel="stylesheet" />
            {% endstylesheets %}
    </head>
    <body>
        {% block content %}
            This page exists for test SmoyaAssetManagementBundle
        {% endblock %}
    
        {% block javascripts %}
            <script src="{{ asset('assetic/main.js') }}"></script>
        {% endblock %}
    
        {# render managed assets #}
        {{ render_assets() }} 
    
        {% block extra %}
    
        {% endblock %}
   </body>
</html>
```
* index.html.twig
``` jinja
    {% extends '::base.html.twig' %}
    {% block content %}
        This page extends from '::base.html.twig' template and i can include code.
        
        {# We need to render a widget #}
        {% render AcmeTestBundle:Test:widget %}
        
    {% endblock %}
    
    {# This one can do because we inherited from the template base, which contains this block below including javascript #}
    {% block extra %}
        <script src="{{ asset('assetic/bar.js' }}" />
    {% endblock %}
```
* widget.html.twig
``` jinja
    {% block widget %}
        I am a widget and I need render Javascript at the bottom of the website code       
    {% endblock %}
    
    {# add_assets adds the inclusion html code for the passed assets in the place where the render_assets() function is called #}
    {{ add_assets('assetic/bar.js', 'js') }}
```

##Options and parameters
Add Assets:    
``` jinja
    {{ add_assets([$ASSETS], $FORMAT, {$ATTR}) }}
```

The parameters:

* 1: **ASSETS** Array/Scalar An array of assets or a single asset. example: ['assetic/foo.js', 'assetic/bar.js']
* 2: **FORMAT** String The format of the assets (js or css)
* 3: **ATTR** Array Optional Associative array of attributes for the inclusion html tag. example for a css asset: {'media': 'screen'}

Print assets:    
``` jinja
    {{ render_assets($FORMAT) }}
```

The parameters:

* 1: **ASSETS** String Optional The format of the assets to print.