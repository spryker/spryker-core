<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilDateTime;

use Codeception\Test\Unit;
use DateTime;
use DateTimeZone;
use Spryker\Service\UtilDateTime\UtilDateTimeService;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Shared\UtilDateTime\UtilDateTimeConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilDateTime
 * @group UtilDateTimeServiceTest
 * Add your own group annotations below this line
 */
class UtilDateTimeServiceTest extends Unit
{
    /**
     * @uses \Spryker\Service\UtilDateTime\Model\DateTimeFormatter::DEFAULT_TIME_ZONE
     *
     * @var string
     */
    protected const DEFAULT_DATE_TIME_ZONE = 'Europe/Berlin';

    /**
     * @var \SprykerTest\Service\UtilDateTime\UtilDateTimeServiceTester
     */
    protected $tester;

    /**
     * @dataProvider dateFormatDataProvider
     *
     * @param \DateTime|string $date
     * @param string $format
     * @param string $expectedFormattedDate
     *
     * @return void
     */
    public function testFormatDateReturnsFormattedDate($date, string $format, string $expectedFormattedDate): void
    {
        $utilDateTimeService = $this->getService([
            UtilDateTimeConstants::DATE_TIME_FORMAT_DATE => $format,
            UtilDateTimeConstants::DATE_TIME_ZONE => 'UTC',
        ]);

        $this->assertSame($expectedFormattedDate, $utilDateTimeService->formatDate($date));
    }

    /**
     * @return array
     */
    public function dateFormatDataProvider(): array
    {
        return [
            ['1980-12-06 08:00:00', 'M. d, Y', 'Dec. 06, 1980'],
            ['1980-12-06 08:00:00', 'Y/m/d', '1980/12/06'],
            ['1980-12-06 08:00:00', 'd.m.Y', '06.12.1980'],
            [new DateTime('1980-12-06 08:00:00', new DateTimeZone('UTC')), 'd.m.Y', '06.12.1980'],
        ];
    }

    /**
     * @dataProvider dateTimeFormatDataProvider
     *
     * @param \DateTime|string $date
     * @param string $format
     * @param string $expectedFormattedDateTime
     *
     * @return void
     */
    public function testFormatDateTimeReturnsFormattedDateTime($date, string $format, string $expectedFormattedDateTime): void
    {
        $utilDateTimeService = $this->getService([
            UtilDateTimeConstants::DATE_TIME_FORMAT_DATE_TIME => $format,
            UtilDateTimeConstants::DATE_TIME_ZONE => 'UTC',
        ]);

        $this->assertSame($expectedFormattedDateTime, $utilDateTimeService->formatDateTime($date));
    }

    /**
     * @return array
     */
    public function dateTimeFormatDataProvider(): array
    {
        return [
            ['1980-12-06 08:00:00', 'M. d, Y H:i', 'Dec. 06, 1980 08:00'],
            ['1980-12-06 08:00:00', 'Y/m/d H:i', '1980/12/06 08:00'],
            ['1980-12-06 08:00:00', 'd.m.Y H:i', '06.12.1980 08:00'],
            [new DateTime('1980-12-06 08:00:00', new DateTimeZone('UTC')), 'd.m.Y H:i', '06.12.1980 08:00'],
        ];
    }

    /**
     * @dataProvider timeFormatDataProvider
     *
     * @param \DateTime|string $date
     * @param string $format
     * @param string $expectedFormattedTime
     *
     * @return void
     */
    public function testFormatTimeReturnsFormattedTime($date, string $format, string $expectedFormattedTime): void
    {
        $utilDateTimeService = $this->getService([
            UtilDateTimeConstants::DATE_TIME_FORMAT_TIME => $format,
            UtilDateTimeConstants::DATE_TIME_ZONE => 'UTC',
        ]);

        $this->assertSame($expectedFormattedTime, $utilDateTimeService->formatTime($date));
    }

    /**
     * @return array
     */
    public function timeFormatDataProvider(): array
    {
        return [
            ['1980-12-06 23:00:00', 'H:i', '23:00'],
            ['1980-12-06 23:00:00', 'h:i', '11:00'],
            [new DateTime('1980-12-06 23:00:00', new DateTimeZone('UTC')), 'h:i', '11:00'],
        ];
    }

    /**
     * @dataProvider testFormatDateTimeToIso8601ReturnsProperlyFormattedDateDataProvider
     *
     * @param \DateTime|string $dateTime
     * @param string|null $timezone
     * @param string $expectedResult
     *
     * @return void
     */
    public function testFormatDateTimeToIso8601ReturnsProperlyFormattedDate($dateTime, ?string $timezone, string $expectedResult): void
    {
        // Act
        $utilDateTimeService = $this->getService([
            UtilDateTimeConstants::DATE_TIME_ZONE => static::DEFAULT_DATE_TIME_ZONE,
        ]);

        $formattedDateTime = $utilDateTimeService->formatDateTimeToIso8601($dateTime, $timezone);

        // Assert
        $this->assertSame($expectedResult, $formattedDateTime);
    }

    /**
     * @return list<array<mixed>>
     */
    protected function testFormatDateTimeToIso8601ReturnsProperlyFormattedDateDataProvider(): array
    {
        return [
            ['1980-12-06 23:59:00', 'UTC', '1980-12-06T23:59:00+00:00'],
            [new DateTime('1980-12-06 23:59:00'), 'UTC', '1980-12-06T23:59:00+00:00'],
            ['1980-12-06 23:59:00', 'EET', '1980-12-07T01:59:00+02:00'],
            [new DateTime('1980-12-06 23:59:00'), 'EET', '1980-12-07T01:59:00+02:00'],
            ['1980-12-06 23:59:00', null, '1980-12-07T00:59:00+01:00'],
            [new DateTime('1980-12-06 23:59:00'), null, '1980-12-07T00:59:00+01:00'],
        ];
    }

    /**
     * @param array $config
     *
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected function getService(array $config): UtilDateTimeServiceInterface
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
    protected function prepareConfig(array $config): void
    {
        foreach ($config as $key => $value) {
            $this->tester->setConfig($key, $value);
        }
    }
}
