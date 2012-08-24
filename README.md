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
<script src('/assetic/foo.js') />
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
``` jinja
{% extends '::base.html.twig' %}
{% block content %}
        This page extends from '::base.html.twig' template and i can include code.
        
        {# We need to render a widget #}
        {% render AcmeTestBundle:Test:widget %}
        
{% endblock %}

{% block extra %}
    <javascript src="{{ asset('assetic/bar.js' }}" />
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
    <javascript src="{{ asset('assetic/another.js' }}" />
{% endblock %}
```

##TODO: This continues...
