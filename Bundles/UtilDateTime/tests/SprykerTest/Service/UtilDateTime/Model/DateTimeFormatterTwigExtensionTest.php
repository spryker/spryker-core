<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilDateTime\Model;

use Codeception\Test\Unit;
use Spryker\Service\UtilDateTime\Model\DateTimeFormatterTwigExtension;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Twig\TwigFilter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilDateTime
 * @group Model
 * @group DateTimeFormatterTwigExtensionTest
 * Add your own group annotations below this line
 */
class DateTimeFormatterTwigExtensionTest extends Unit
{
    public const DATE_TO_FORMAT = '1980-12-06 08:00:00';

    /**
     * @return void
     */
    public function testInstantiationShouldReturnExtension(): void
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);

        $this->assertInstanceOf(DateTimeFormatterTwigExtension::class, $dateTimeFormatterTwigExtension);
    }

    /**
     * @return void
     */
    public function testGetNameReturnsNameOfExtension(): void
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);

        $this->assertSame(DateTimeFormatterTwigExtension::EXTENSION_NAME, $dateTimeFormatterTwigExtension->getName());
    }

    /**
     * @return void
     */
    public function testGetFiltersShouldReturnArray(): void
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);

        $this->assertContainsOnly(TwigFilter::class, $dateTimeFormatterTwigExtension->getFilters());
    }

    /**
     * @return void
     */
    public function testGetFunctionsShouldReturnArray(): void
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);

        $this->assertIsArray($dateTimeFormatterTwigExtension->getFunctions());
    }

    /**
     * @return void
     */
    public function testFormatDateDelegatesToDateTimeFormatter(): void
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $utilDateTimeServiceMock->expects(self::once())->method('formatDate');

        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);
        $dateTimeFormatterTwigExtension->formatDate(self::DATE_TO_FORMAT);
    }

    /**
     * @return void
     */
    public function testFormatDateTimeDelegatesToDateTimeFormatter(): void
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $utilDateTimeServiceMock->expects(self::once())->method('formatDateTime');

        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);
        $dateTimeFormatterTwigExtension->formatDateTime(self::DATE_TO_FORMAT);
    }

    /**
     * @return void
     */
    public function testFormatTimeDelegatesToDateTimeFormatter(): void
    {
        $utilDateTimeServiceMock = $this->getUtilDateTimeServiceMock();
        $utilDateTimeServiceMock->expects(self::once())->method('formatTime');

        $dateTimeFormatterTwigExtension = new DateTimeFormatterTwigExtension($utilDateTimeServiceMock);
        $dateTimeFormatterTwigExtension->formatTime(self::DATE_TO_FORMAT);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected function getUtilDateTimeServiceMock(): UtilDateTimeServiceInterface
    {
        $utilDateTimeServiceMock = $this->getMockBuilder(UtilDateTimeServiceInterface::class)->getMock();

        return $utilDateTimeServiceMock;
    }
}
