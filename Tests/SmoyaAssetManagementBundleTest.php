<?php
namespace Smoya\Bundle\AssetManagementBundle\Tests;

use Smoya\Bundle\AssetManagementBundle\SmoyaAssetManagementBundle;

class SmoyaAssetManagementBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testIsEnabled()
    {
        $bundle = new SmoyaAssetManagementBundle();
        $this->assertInstanceOf('Symfony\Component\HttpKernel\Bundle\Bundle', $bundle);
    }
}
