<?php

namespace Smoya\Bundle\AssetManagementBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AssetManagementExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'add_assets' => new \Twig_Function_Method($this, 'add'),
            'render_assets' => new \Twig_Function_Method($this, 'render', array('is_safe' => array('html'))),
        );
    }

    public function add($packages, $format, array $attributes = array())
    {
        $this->container->get('asset_management.templating.helper')->add($packages, $format, $attributes);
    }

    public function render($format = null)
    {
        return $this->container->get('asset_management.templating.helper')->render($format);
    }

    public function getName()
    {
        return 'assetmanagement';
    }
}
