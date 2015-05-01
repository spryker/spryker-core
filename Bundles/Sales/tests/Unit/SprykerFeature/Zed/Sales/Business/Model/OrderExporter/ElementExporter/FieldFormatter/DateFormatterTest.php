<?php

namespace Unit\SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter;

use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter\DateFormatter;

/**
 * Class DateFormatterTest
 * @package Unit\SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\Decorator
 * @group OrderExporter
 * @group FieldFormatter
 */
class DateFormatterTest extends \PHPUnit_Framework_TestCase
{

    public function testFormatShouldReturnDefinedFormat()
    {
        $formatter = new DateFormatter('date', 'Ymd');
        $this->assertSame('20131111', $formatter->format('2013-11-11 13:11:03'));
    }
}
