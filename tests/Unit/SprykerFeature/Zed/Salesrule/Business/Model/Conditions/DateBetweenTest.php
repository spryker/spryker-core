<?php

namespace Unit\SprykerFeature\Zed\Salesrule\Business\Model\Conditions;

use SprykerFeature\Shared\Library\TransferLoader;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Salesrule\Business\Model\Conditions\DateBetween;

/**
 * Class DateBetweenTest
 * @package Unit\SprykerFeature\Zed\Salesrule\Business\Model\Conditions
 *
 * @group Salesrule
 * @group DateBetween
 */
class DateBetweenTest extends \PHPUnit_Framework_TestCase
{
    const TIME_OFFSET = 1000;

    public function testDateBetweenShouldReturnTrueForADateInBetweenTheGivenRange()
    {
        $configuration = [
            'start_date' => date('Y-m-d H:i:s', time()),
            'end_date' => date('Y-m-d H:i:s', time() + self::TIME_OFFSET)
        ];
        $condition = new \SprykerFeature\Zed\Salesrule\Business\Model\Condition\DateBetween($configuration);
        $this->assertTrue($condition->match(Locator::getInstance()->sales()->transferOrder()));
    }

    public function testDateBetweenShouldReturnFalseForADateBeforeTheGivenRange()
    {
        $now = time();
        $configuration = [
            'start_date' => date('Y-m-d H:i:s', $now - self::TIME_OFFSET),
            'end_date' => date('Y-m-d H:i:s', $now - self::TIME_OFFSET / 2)
        ];
        $condition = new \SprykerFeature\Zed\Salesrule\Business\Model\Condition\DateBetween($configuration);
        $this->assertFalse($condition->match(Locator::getInstance()->sales()->transferOrder()));
    }

    public function testDateBetweenShouldReturnFalseForADateAfterTheGivenRange()
    {
        $now = time();
        $configuration = [
            'start_date' => date('Y-m-d H:i:s', $now + self::TIME_OFFSET),
            'start_date' => date('Y-m-d H:i:s', $now + self::TIME_OFFSET * 2)
        ];
        $condition = new \SprykerFeature\Zed\Salesrule\Business\Model\Condition\DateBetween($configuration);
        $this->assertFalse($condition->match(Locator::getInstance()->sales()->transferOrder()));
    }
}
