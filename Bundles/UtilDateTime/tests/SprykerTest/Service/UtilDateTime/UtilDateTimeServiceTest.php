<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilDateTime;

use Codeception\Test\Unit;
use DateTime;
use DateTimeZone;
use ReflectionClass;
use Spryker\Service\UtilDateTime\UtilDateTimeService;
use Spryker\Shared\Config\Config;
use Spryker\Shared\UtilDateTime\UtilDateTimeConstants;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group UtilDateTime
 * @group UtilDateTimeServiceTest
 * Add your own group annotations below this line
 */
class UtilDateTimeServiceTest extends Unit
{

    /**
     * @dataProvider dateFormatDataProvider
     *
     * @param string $date
     * @param string $format
     * @param string $expectedFormattedDate
     *
     * @return void
     */
    public function testFormatDateReturnsFormattedDate($date, $format, $expectedFormattedDate)
    {
        $utilDateTimeService = $this->getService([UtilDateTimeConstants::DATE_TIME_FORMAT_DATE => $format]);

        $this->assertSame($expectedFormattedDate, $utilDateTimeService->formatDate($date));
    }

    /**
     * @return array
     */
    public function dateFormatDataProvider()
    {
        return [
            ['1980-12-06 08:00:00', 'M. d, Y', 'Dec. 06, 1980'],
            ['1980-12-06 08:00:00', 'Y/m/d', '1980/12/06'],
            ['1980-12-06 08:00:00', 'd.m.Y', '06.12.1980'],
            [new DateTime('1980-12-06 08:00:00', new DateTimeZone('Europe/Berlin')), 'd.m.Y', '06.12.1980'],
        ];
    }

    /**
     * @dataProvider dateTimeFormatDataProvider
     *
     * @param string $date
     * @param string $format
     * @param string $expectedFormattedDateTime
     *
     * @return void
     */
    public function testFormatDateTimeReturnsFormattedDateTime($date, $format, $expectedFormattedDateTime)
    {
        $utilDateTimeService = $this->getService([UtilDateTimeConstants::DATE_TIME_FORMAT_DATE_TIME => $format]);

        $this->assertSame($expectedFormattedDateTime, $utilDateTimeService->formatDateTime($date));
    }

    /**
     * @return array
     */
    public function dateTimeFormatDataProvider()
    {
        return [
            ['1980-12-06 08:00:00', 'M. d, Y H:i', 'Dec. 06, 1980 08:00'],
            ['1980-12-06 08:00:00', 'Y/m/d H:i', '1980/12/06 08:00'],
            ['1980-12-06 08:00:00', 'd.m.Y H:i', '06.12.1980 08:00'],
            [new DateTime('1980-12-06 08:00:00', new DateTimeZone('Europe/Berlin')), 'd.m.Y H:i', '06.12.1980 08:00'],
        ];
    }

    /**
     * @dataProvider timeFormatDataProvider
     *
     * @param string $date
     * @param string $format
     * @param string $expectedFormattedTime
     *
     * @return void
     */
    public function testFormatTimeReturnsFormattedTime($date, $format, $expectedFormattedTime)
    {
        $utilDateTimeService = $this->getService([UtilDateTimeConstants::DATE_TIME_FORMAT_TIME => $format]);

        $this->assertSame($expectedFormattedTime, $utilDateTimeService->formatTime($date));
    }

    /**
     * @return array
     */
    public function timeFormatDataProvider()
    {
        return [
            ['1980-12-06 23:00:00', 'H:i', '23:00'],
            ['1980-12-06 23:00:00', 'h:i', '11:00'],
            [new DateTime('1980-12-06 23:00:00', new DateTimeZone('Europe/Berlin')), 'h:i', '11:00'],
        ];
    }

    /**
     * @param array $config
     *
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected function getService(array $config)
    {
        $this->prepareConfig($config);
        $utilDateTimeService = new UtilDateTimeService();

        return $utilDateTimeService;
    }

    /**
     * @param array $config
     *
     * @return void
     */
    protected function prepareConfig(array $config)
    {
        Config::init();
        $reflectionClass = new ReflectionClass(Config::class);
        $reflectionProperty = $reflectionClass->getProperty('config');
        $reflectionProperty->setAccessible(true);
        $configuration = $reflectionProperty->getValue();

        foreach ($config as $key => $value) {
            $configuration[$key] = $value;
        }

        $reflectionProperty->setValue($configuration);
    }

}
