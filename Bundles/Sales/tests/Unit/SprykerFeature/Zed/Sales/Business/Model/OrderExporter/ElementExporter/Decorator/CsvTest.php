<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\Decorator;

use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\Decorator\CsvDecorator;

/**
 * Class CsvTest
 * @package Unit\SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\Decorator
 * @group OrderExporter
 * @group Decorator
 */
class CsvTest extends \PHPUnit_Framework_TestCase
{

    const EXPECTED_RESULT = 'fieldValueA;fieldValueB';
    const EXPECTED_RESULT_WITH_TRAILING_SEPARATOR = 'fieldValueA;fieldValueB;';

    public function testDecorateShouldReturnDecoratedString()
    {
        $decorator = new CsvDecorator([CsvDecorator::SEPARATOR => ';']);
        $result = $decorator->decorate(['fieldKeyA' => 'fieldValueA', 'fieldKeyB' => 'fieldValueB']);
        $this->assertSame(self::EXPECTED_RESULT, $result);
    }

    public function testDecorateShouldAddTrailingSeparatorIfDefined()
    {
        $decorator = new CsvDecorator([CsvDecorator::SEPARATOR => ';', CsvDecorator::ADD_TRAILING_SEPARATOR => true]);
        $result = $decorator->decorate(['fieldKeyA' => 'fieldValueA', 'fieldKeyB' => 'fieldValueB']);
        $this->assertSame(self::EXPECTED_RESULT_WITH_TRAILING_SEPARATOR, $result);
    }

    public function testDecorateShouldThrowExceptionIfNoSeparator()
    {
        $this->setExpectedException('SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\Decorator\DecoratorException');
        $decorator = new CsvDecorator();
        $result = $decorator->decorate(['fieldKeyA' => 'fieldValueA', 'fieldKeyB' => 'fieldValueB']);
    }
}
