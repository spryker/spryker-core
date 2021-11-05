<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Communication\Form\DataProvider;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Zed\ProductManagement\Communication\Form\DataProvider\AbstractProductFormDataProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Communication
 * @group Form
 * @group DataProvider
 * @group AbstractProductFormDataProviderTest
 * Add your own group annotations below this line
 */
class AbstractProductFormDataProviderTest extends Unit
{
    /**
     * @var string
     */
    public const PIM_IMAGE_URL = '/foo/bar.jpg';

    /**
     * @var string
     */
    public const SECURE_CDN_IMAGE_URL = 'https://example.com/bar.jpg';

    /**
     * @var string
     */
    public const NON_SECURE_CDN_IMAGE_URL = 'http://example.com/bar.jpg';

    /**
     * @var string
     */
    public const CDN_IMAGE_URL = '//example.com/bar.jpg';

    /**
     * @var string
     */
    public const IMAGE_URL_PREFIX = 'IMAGE_URL_PREFIX';

    /**
     * @return void
     */
    public function testGetImageUrl(): void
    {
        $productFormDataProviderMock = $this->getProductFormDataProviderMock();

        $reflectionClass = new ReflectionClass(get_class($productFormDataProviderMock));
        $reflectionProperty = $reflectionClass->getProperty('imageUrlPrefix');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($productFormDataProviderMock, static::IMAGE_URL_PREFIX);
        $reflectionMethod = $reflectionClass->getMethod('getImageUrl');
        $reflectionMethod->setAccessible(true);

        $testData = $this->prepareTestData();

        foreach ($testData as $expectedUrl => $originalUrl) {
            $url = $reflectionMethod->invoke($productFormDataProviderMock, $originalUrl);
            $this->assertSame($expectedUrl, $url);
        }
    }

    /**
     * @return array
     */
    public function prepareTestData(): array
    {
        $data = [
            static::IMAGE_URL_PREFIX . static::PIM_IMAGE_URL => static::PIM_IMAGE_URL,
            static::SECURE_CDN_IMAGE_URL => static::SECURE_CDN_IMAGE_URL,
            static::NON_SECURE_CDN_IMAGE_URL => static::NON_SECURE_CDN_IMAGE_URL,
            static::CDN_IMAGE_URL => static::CDN_IMAGE_URL,
        ];

        return $data;
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Communication\Form\DataProvider\AbstractProductFormDataProvider|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getProductFormDataProviderMock(): AbstractProductFormDataProvider
    {
        $productFormDataProviderMock = $this->getMockBuilder(AbstractProductFormDataProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $productFormDataProviderMock;
    }
}
