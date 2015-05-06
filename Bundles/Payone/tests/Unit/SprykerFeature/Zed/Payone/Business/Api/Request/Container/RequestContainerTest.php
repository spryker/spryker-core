<?php

namespace Unit\SprykerFeature\Zed\Payone\Business\Api\Request\Container;


use SprykerFeature\Zed\Payone\Business\Api\Request\Container\RefundContainer;

/**
 * @group RequestContainer
 */
class RequestContainerTest extends \PHPUnit_Framework_TestCase
{

    protected $amount = 9900;
    protected $encoding = 'UTF-8';
    protected $currency = 'EUR';
    protected $sequenceNumber = 2;
    protected $mode = 'test';
    protected $txId = '123456789';
    protected $portalId = '12345';
    protected $mid = '123';
    protected $aid = '1234';

    public function testRefundContainer()
    {
        $refundContainer = new RefundContainer();

        $refundContainer->setAmount($this->amount);
        $refundContainer->setEncoding($this->encoding);
        $refundContainer->setCurrency($this->currency);
        $refundContainer->setSequenceNumber($this->sequenceNumber);
        $refundContainer->setMode($this->mode);
        $refundContainer->setTxid($this->txId);
        $refundContainer->setPortalid($this->portalId);
        $refundContainer->setMid($this->mid);

        $this->assertEquals($this->amount, $refundContainer->getAmount());
        $this->assertEquals($this->encoding, $refundContainer->getEncoding());
        $this->assertEquals($this->currency, $refundContainer->getCurrency());
        $this->assertEquals($this->sequenceNumber, $refundContainer->getSequenceNumber());
        $this->assertEquals($this->mode, $refundContainer->getMode());
        $this->assertEquals($this->txId, $refundContainer->getTxid());
        $this->assertEquals($this->portalId, $refundContainer->getPortalid());
        $this->assertEquals($this->mid, $refundContainer->getMid());
    }

}
