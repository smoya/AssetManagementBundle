<?php

namespace Smoya\Bundle\AssetManagementBundle\Templating\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\Helper\Helper;

class AssetManagementHelper extends Helper
{
    protected $packages;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->packages = array();
    }

    /**
     * Adds an asset package.
     *
     * @param  string                    $packages   An asset package
     * @param  string                    $format     The asset format
     * @param  array                     $attributes An array of attributes
     * @throws \InvalidArgumentException
     * @return void
     */
    public function add($packages, $format, $attributes = array())
    {
        if (false === $this->isValidFormat($format)) {
            throw new \InvalidArgumentException(sprintf('The AssetManagementHelper does not support the format: \'%s\'.', $format));
        }

        if ($packages instanceof \Twig_Markup)
        {
            $packages = (string) $packages;
        }

        if (is_scalar($packages)) {
            $packages = array($packages);
        }

        foreach ($packages as $package) {
            $this->packages[$format][$package] = $attributes;
        }
    }

    /**
     * Returns HTML inclusion code of the asset packages
     *
     * @param  string                    $format
     * @throws \InvalidArgumentException
     * @return string                    The HTML inclusion code of the asset packages
     */
    public function render($format = null)
    {
        if ($format && false === $this->isValidFormat($format)) {
            throw new \InvalidArgumentException(sprintf('The AssetManagementHelper does not support the format: \'%s\'.', $format));
        }

        $html = '';

        if ($format && array_key_exists($format, $this->packages)) {
            $html .= $this->doRender($this->packages[$format], $format);
        } elseif (null === $format) {
            foreach ($this->packages as $packageFormat => $package) {
                $html .= $this->doRender($this->packages[$packageFormat], $packageFormat);
            }
        }

        return $html;
    }

    protected function doRender(array $packages, $format)
    {
        $html = array();
        foreach ($packages as $packageName => $attributes) {
                $html[] = $this->renderTags($packageName, $format, $attributes);
        }

        return implode('', $html);
    }

    protected function isValidFormat($format)
    {
        return in_array($format, array('js', 'css', 'inline_js', 'inline_css'));
    }

    /**
     * Render html inclusion tags.
     *
     * @param  string $path
     * @param  string $format
     * @param  array  $attributes
     * @return String html inclusion tags
     */
    protected function renderTags($path, $format, array $attributes = array())
    {
        $attr = array();
        foreach ($attributes as $key => $value) {
            $attr[] = sprintf('%s=%s', $key, $value);
        }

        return $this->container->get('templating')->render(sprintf('SmoyaAssetManagementBundle:Helper:%s_tag.html.twig', $format), array('path' => $path, 'attr' => implode(' ', $attr)));
    }

    public function __toString()
    {
        return $this->render();
    }

    public function getName()
    {
        return 'assetmanagement';
    }
}
