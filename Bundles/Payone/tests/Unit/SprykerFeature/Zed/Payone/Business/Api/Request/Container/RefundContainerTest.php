<?php

namespace Unit\SprykerFeature\Zed\Calculation\Business\Model\Calculator;


use SprykerFeature\Zed\Payone\Business\Api\Request\Container\RefundContainer;

/**
 * Class RefundContainerTest
 * @group RefundContainerTest
 * @group Container
 * @package PhpUnit\SprykerFeature\Zed\Payone\Communication\Plugin
 */
class RefundContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testRefundContainerGetters()
    {
        $amount = 9900;
        $refundContainer = new RefundContainer();
        $refundContainer->setAmount($amount);

        $this->assertEquals($amount, $refundContainer->getAmount());
    }

}
