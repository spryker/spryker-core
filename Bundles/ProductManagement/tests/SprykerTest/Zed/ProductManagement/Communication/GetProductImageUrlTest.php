<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Communication;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Zed\ProductManagement\Communication\Controller\ViewController;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\AbstractProductFormDataProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Communication
 * @group GetProductImageUrlTest
 * Add your own group annotations below this line
 */
class GetProductImageUrlTest extends Unit
{
    const PIM_IMAGE_URL = '/foo/bar.jpg';
    const SECURE_CDN_IMAGE_URL = 'https://example.com/bar.jpg';
    const NON_SECURE_CDN_IMAGE_URL = 'http://example.com/bar.jpg';
    const CDN_IMAGE_URL = '//example.com/bar.jpg';
    const IMAGE_URL_PREFIX = 'IMAGE_URL_PREFIX';

    /**
     * @return void
     */
    public function testGetProductImageUrlUsingFormDataProvider()
    {
        $productFormDataProviderMock = $this->getProductFormDataProviderMock();

        $reflectionClass = new ReflectionClass(get_class($productFormDataProviderMock));
        $reflectionProperty = $reflectionClass->getProperty('imageUrlPrefix');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($productFormDataProviderMock, static::IMAGE_URL_PREFIX);
        $reflectionMethod = $reflectionClass->getMethod('getImageUrl');
        $reflectionMethod->setAccessible(true);

        $pimImageUrl = $this->getPimImageUrl();
        $secureCdnImageUrl = $this->getSecureCdnImageUrl();
        $nonSecureCdnImageUrl = $this->getNonSecureCdnImageUrl();
        $cdnImageUrl = $this->getCdnImageUrl();

        $expectedPimImageUrl = static::IMAGE_URL_PREFIX . $pimImageUrl;
        $expectedSecureCdnImageUrl = $secureCdnImageUrl;
        $expectedNonSecureCdnImageUrl = $nonSecureCdnImageUrl;
        $expectedCdnImageUrl = $cdnImageUrl;

        $pimImageUrl = $reflectionMethod->invoke($productFormDataProviderMock, $pimImageUrl);
        $secureCdnImageUrl = $reflectionMethod->invoke($productFormDataProviderMock, $secureCdnImageUrl);
        $nonSecureCdnImageUrl = $reflectionMethod->invoke($productFormDataProviderMock, $nonSecureCdnImageUrl);
        $cdnImageUrl = $reflectionMethod->invoke($productFormDataProviderMock, $cdnImageUrl);

        $this->assertEquals($expectedPimImageUrl, $pimImageUrl);
        $this->assertEquals($expectedSecureCdnImageUrl, $secureCdnImageUrl);
        $this->assertEquals($expectedNonSecureCdnImageUrl, $nonSecureCdnImageUrl);
        $this->assertEquals($expectedCdnImageUrl, $cdnImageUrl);
    }

    /**
     * @return void
     */
    public function testGetProductImageUrlUsingViewController()
    {
        $vieControllerMock = $this->getViewControllerMock();

        $reflectionClass = new ReflectionClass(get_class($vieControllerMock));
        $reflectionMethod = $reflectionClass->getMethod('getImageUrl');
        $reflectionMethod->setAccessible(true);

        $pimImageUrl = $this->getPimImageUrl();
        $secureCdnImageUrl = $this->getSecureCdnImageUrl();
        $nonSecureCdnImageUrl = $this->getNonSecureCdnImageUrl();
        $cdnImageUrl = $this->getCdnImageUrl();

        $expectedPimImageUrl = static::IMAGE_URL_PREFIX . $pimImageUrl;
        $expectedSecureCdnImageUrl = $secureCdnImageUrl;
        $expectedNonSecureCdnImageUrl = $nonSecureCdnImageUrl;
        $expectedCdnImageUrl = $cdnImageUrl;

        $pimImageUrl = $reflectionMethod->invoke($vieControllerMock, $pimImageUrl, static::IMAGE_URL_PREFIX);
        $secureCdnImageUrl = $reflectionMethod->invoke($vieControllerMock, $secureCdnImageUrl, static::IMAGE_URL_PREFIX);
        $nonSecureCdnImageUrl = $reflectionMethod->invoke($vieControllerMock, $nonSecureCdnImageUrl, static::IMAGE_URL_PREFIX);
        $cdnImageUrl = $reflectionMethod->invoke($vieControllerMock, $cdnImageUrl, static::IMAGE_URL_PREFIX);

        $this->assertEquals($expectedPimImageUrl, $pimImageUrl);
        $this->assertEquals($expectedSecureCdnImageUrl, $secureCdnImageUrl);
        $this->assertEquals($expectedNonSecureCdnImageUrl, $nonSecureCdnImageUrl);
        $this->assertEquals($expectedCdnImageUrl, $cdnImageUrl);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getProductFormDataProviderMock()
    {
        $productFormDataProviderMock = $this->getMockBuilder(AbstractProductFormDataProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $productFormDataProviderMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getViewControllerMock()
    {
        $vieControllerMock = $this->getMockBuilder(ViewController::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $vieControllerMock;
    }

    /**
     * @return string
     */
    protected function getPimImageUrl()
    {
        return self::PIM_IMAGE_URL;
    }

    /**
     * @return string
     */
    protected function getSecureCdnImageUrl()
    {
        return self::SECURE_CDN_IMAGE_URL;
    }

    /**
     * @return string
     */
    protected function getNonSecureCdnImageUrl()
    {
        return self::NON_SECURE_CDN_IMAGE_URL;
    }

    /**
     * @return string
     */
    protected function getCdnImageUrl()
    {
        return self::CDN_IMAGE_URL;
    }
}
