<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\SequenceNumber\Business\Generator\RandomNumberGenerator;

class OrderNumberGeneratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return OrderSequenceInterface
     */
    protected function createMockOrderSequence()
    {
        $randomNumberGenerator = new RandomNumberGenerator(1, 1);

        $orderSequence = $this->getMockBuilder('SprykerFeature\Zed\Sales\Business\Model\OrderSequence')
            ->setConstructorArgs([$randomNumberGenerator, 1000])
            ->setMethods(['generate'])
            ->getMock()
        ;

        $orderSequence->expects($this->any())
            ->method('generate')
            ->will($this->returnValue(1234))
        ;

        return $orderSequence;
    }

    /**
     * @return OrderReferenceGeneratorInterface
     */
    protected function createDevelopmentOrderReferenceGenerator()
    {
        return new OrderReferenceGenerator(
            $this->createMockOrderSequence(),
            true,
            false,
            'TEST'
        );
    }

    /**
     * @return OrderReferenceGeneratorInterface
     */
    protected function createProductionOrderReferenceGenerator()
    {
        return new OrderReferenceGenerator(
            $this->createMockOrderSequence(),
            false,
            false,
            'TEST'
        );
    }

    public function testOrderReferenceForProduction()
    {
        $transferOrder = new OrderTransfer();
        $orderReference = $this->createProductionOrderReferenceGenerator()->generateOrderReference($transferOrder);
        $this->assertEquals('P-TEST-1234', $orderReference);
    }

    public function testOrderReferenceForDevelopment()
    {
        $transferOrder = new OrderTransfer();
        $orderReference = $this->createDevelopmentOrderReferenceGenerator()->generateOrderReference($transferOrder);
        $this->assertTrue(substr($orderReference, 0, strlen('D-TEST-')) === 'D-TEST-');
    }

}
