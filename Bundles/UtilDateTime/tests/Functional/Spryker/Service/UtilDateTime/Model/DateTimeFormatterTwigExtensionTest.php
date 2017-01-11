<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Service\UtilDateTime\Model;

use PHPUnit_Framework_TestCase;
use Spryker\Service\UtilDateTime\Model\DateTimeFormatterTwigExtension;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Shared\Twig\TwigFilter;

/**
 * @group Functional
 * @group Spryker
 * @group Service
 * @group UtilDateTime
 * @group Model
 * @group DateTimeFormatterTwigExtensionTest
 */
class DateTimeFormatterTwigExtensionTest extends PHPUnit_Framework_TestCase
{

    const DATE_TO_FORMAT = '1980-12-06 08:00:00';

    /**
     * @return void
     */
    public function testInstantiationShouldReturnExtension()
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);

        $this->assertInstanceOf(DateTimeFormatterTwigExtension::class, $dateTimeFormatterTwigExtension);
    }

    /**
     * @return void
     */
    public function testGetNameReturnsNameOfExtension()
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);

        $this->assertSame(DateTimeFormatterTwigExtension::EXTENSION_NAME, $dateTimeFormatterTwigExtension->getName());
    }

    /**
     * @return void
     */
    public function testGetFiltersShouldReturnArray()
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);

        $this->assertContainsOnly(TwigFilter::class, $dateTimeFormatterTwigExtension->getFilters());
    }

    /**
     * @return void
     */
    public function testGetFunctionsShouldReturnArray()
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);

        $this->assertInternalType('array', $dateTimeFormatterTwigExtension->getFunctions());
    }

    /**
     * @return void
     */
    public function testFormatDateDelegatesToDateTimeFormatter()
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $utilDateTimeServiceMock->expects(self::once())->method('formatDate');

        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);
        $dateTimeFormatterTwigExtension->formatDate(self::DATE_TO_FORMAT);
    }

    /**
     * @return void
     */
    public function testFormatDateTimeDelegatesToDateTimeFormatter()
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $utilDateTimeServiceMock->expects(self::once())->method('formatDateTime');

        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);
        $dateTimeFormatterTwigExtension->formatDateTime(self::DATE_TO_FORMAT);
    }

    /**
     * @return void
     */
    public function testFormatTimeDelegatesToDateTimeFormatter()
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $utilDateTimeServiceMock->expects(self::once())->method('formatTime');

        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);
        $dateTimeFormatterTwigExtension->formatTime(self::DATE_TO_FORMAT);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected function getUtilDateTimeServiceMock()
    {
        $utilDateTimeServiceMock = $this->getMockBuilder(UtilDateTimeServiceInterface::class)->getMock();

        return $utilDateTimeServiceMock;
    }

}
