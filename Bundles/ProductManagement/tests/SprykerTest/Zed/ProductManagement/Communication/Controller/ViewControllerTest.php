<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Communication\From\DataProvider;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Zed\ProductManagement\Communication\Controller\ViewController;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Communication
 * @group From
 * @group DataProvider
 * @group ViewControllerTest
 * Add your own group annotations below this line
 */
class ViewControllerTest extends Unit
{
    public const PIM_IMAGE_URL = '/foo/bar.jpg';
    public const SECURE_CDN_IMAGE_URL = 'https://example.com/bar.jpg';
    public const NON_SECURE_CDN_IMAGE_URL = 'http://example.com/bar.jpg';
    public const CDN_IMAGE_URL = '//example.com/bar.jpg';
    public const IMAGE_URL_PREFIX = 'IMAGE_URL_PREFIX';

    /**
     * @return void
     */
    public function testGetImageUrl()
    {
        $vieControllerMock = $this->getViewControllerMock();

        $reflectionClass = new ReflectionClass(get_class($vieControllerMock));
        $reflectionMethod = $reflectionClass->getMethod('getImageUrl');
        $reflectionMethod->setAccessible(true);

        $testData = $this->prepareTestData();

        foreach ($testData as $expectedUrl => $originalUrl) {
            $url = $reflectionMethod->invoke($vieControllerMock, $originalUrl, static::IMAGE_URL_PREFIX);
            $this->assertEquals($expectedUrl, $url);
        }
    }

    /**
     * @return array
     */
    public function prepareTestData()
    {
        $data = [
            self::IMAGE_URL_PREFIX . self::PIM_IMAGE_URL => self::PIM_IMAGE_URL,
            self::SECURE_CDN_IMAGE_URL => self::SECURE_CDN_IMAGE_URL,
            self::NON_SECURE_CDN_IMAGE_URL => self::NON_SECURE_CDN_IMAGE_URL,
            self::CDN_IMAGE_URL => self::CDN_IMAGE_URL,
        ];

        return $data;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getViewControllerMock()
    {
        $vieControllerMock = $this->getMockBuilder(ViewController::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $vieControllerMock;
    }
}
