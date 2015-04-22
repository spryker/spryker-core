<?php

class SprykerFeature_Zed_Salesrule_Test_Model_Conditions_ValidToTest extends PHPUnit_Framework_TestCase
{
    const TIME_OFFSET = 1000;

    public function testValidTo()
    {
        $this->markTestSkipped();
        $configuration = array('date' => date('Y-m-d H:i:s', time() + self::TIME_OFFSET));
        $condition = new \SprykerFeature\Zed\Salesrule\Business\Model\Condition\ValidTo($configuration);

        $this->assertTrue($condition->match(\SprykerEngine\Zed\Kernel\Locator::getInstance()->sales()->transferOrder()));

        $configuration = array('date' => date('Y-m-d H:i:s', time() - self::TIME_OFFSET));
        $condition = new \SprykerFeature\Zed\Salesrule\Business\Model\Condition\ValidTo($configuration);
        $this->assertFalse($condition->match(\SprykerEngine\Zed\Kernel\Locator::getInstance()->sales()->transferOrder()));

        $salesOrderTransfer = \SprykerEngine\Zed\Kernel\Locator::getInstance()->sales()->transferOrder();
        $this->assertTrue($condition->match($salesOrderTransfer));

        $configuration = array('date' => date('Y-m-d H:i:s', time() - self::TIME_OFFSET));
        $condition = new \SprykerFeature\Zed\Salesrule\Business\Model\Condition\ValidTo($configuration);
        $this->assertFalse($condition->match($salesOrderTransfer));
    }
}
