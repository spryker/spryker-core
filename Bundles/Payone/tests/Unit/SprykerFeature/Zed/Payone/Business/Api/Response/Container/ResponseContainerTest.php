<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Payone\Business\Api\Response\Container;

use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerFeature\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;

/**
 * @group ResponseContainer
 */
class ResponseContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testAuthorizationResponseContainer()
    {
        $params = array_merge($this->getStandardResponseParams(), $this->getAuthorizationResponseParams());
        $container = new AuthorizationResponseContainer($params);

        $this->assertInstanceOf('SprykerFeature\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer', $container);
        $this->assertStandardParams($container);
        $this->assertEquals('clearingamount', $container->getClearingAmount());
        $this->assertEquals('clearingbankaccount', $container->getClearingBankaccount());
        $this->assertEquals('clearingbankaccountholder', $container->getClearingBankaccountholder());
        $this->assertEquals('clearingbankbic', $container->getClearingBankbic());
        $this->assertEquals('clearingbankcity', $container->getClearingBankcity());
        $this->assertEquals('clearingbankcode', $container->getClearingBankcode());
        $this->assertEquals('clearingbankcountry', $container->getClearingBankcountry());
        $this->assertEquals('clearingbankiban', $container->getClearingBankiban());
        $this->assertEquals('clearingbankname', $container->getClearingBankname());
        $this->assertEquals('clearingdate', $container->getClearingDate());
        $this->assertEquals('creditorcity', $container->getCreditorCity());
        $this->assertEquals('creditorcountry', $container->getCreditorCountry());
        $this->assertEquals('creditoremail', $container->getCreditorEmail());
        $this->assertEquals('creditoridentifier', $container->getCreditorIdentifier());
        $this->assertEquals('creditorname', $container->getCreditorName());
        $this->assertEquals('creditorstreet', $container->getCreditorStreet());
        $this->assertEquals('creditorzip', $container->getCreditorZip());
    }

    protected function getAuthorizationResponseParams()
    {
        return [
            'clearingamount' => 'clearingamount',
            'clearingbankaccount' => 'clearingbankaccount',
            'clearingbankaccountholder' => 'clearingbankaccountholder',
            'clearingbankbic' => 'clearingbankbic',
            'clearingbankcity' => 'clearingbankcity',
            'clearingbankcode' => 'clearingbankcode',
            'clearingbankcountry' => 'clearingbankcountry',
            'clearingbankiban' => 'clearingbankiban',
            'clearingbankname' => 'clearingbankname',
            'clearingdate' => 'clearingdate',

            'creditorcity' => 'creditorcity',
            'creditorcountry' => 'creditorcountry',
            'creditoremail' => 'creditoremail',
            'creditoridentifier' => 'creditoridentifier',
            'creditorname' => 'creditorname',
            'creditorstreet' => 'creditorstreet',
            'creditorzip' => 'creditorzip',
        ];
    }

    public function testCaptureResponseContainer()
    {
        $params = array_merge($this->getStandardResponseParams(), $this->getCaptureResponseParams());
        $container = new CaptureResponseContainer($params);

        $this->assertInstanceOf('SprykerFeature\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer', $container);
        $this->assertStandardParams($container);
        $this->assertEquals('clearingamount', $container->getClearingAmount());
        $this->assertEquals('clearingbankaccount', $container->getClearingBankaccount());
        $this->assertEquals('clearingbankaccountholder', $container->getClearingBankaccountholder());
        $this->assertEquals('clearingbankbic', $container->getClearingBankbic());
        $this->assertEquals('clearingbankcity', $container->getClearingBankcity());
        $this->assertEquals('clearingbankcode', $container->getClearingBankcode());
        $this->assertEquals('clearingbankcountry', $container->getClearingBankcountry());
        $this->assertEquals('clearingbankiban', $container->getClearingBankiban());
        $this->assertEquals('clearingbankname', $container->getClearingBankname());
        $this->assertEquals('clearingdate', $container->getClearingDate());
        $this->assertEquals('clearingduedate', $container->getClearingDuedate());
        $this->assertEquals('clearinginstructionnote', $container->getClearingInstructionnote());
        $this->assertEquals('clearinglegalnote', $container->getClearingLegalnote());
        $this->assertEquals('clearingreference', $container->getClearingReference());

        $this->assertEquals('creditorcity', $container->getCreditorCity());
        $this->assertEquals('creditorcountry', $container->getCreditorCountry());
        $this->assertEquals('creditoremail', $container->getCreditorEmail());
        $this->assertEquals('creditoridentifier', $container->getCreditorIdentifier());
        $this->assertEquals('creditorname', $container->getCreditorName());
        $this->assertEquals('creditorstreet', $container->getCreditorStreet());
        $this->assertEquals('creditorzip', $container->getCreditorZip());

        $this->assertEquals('mandateidentification', $container->getMandateIdentification());
        $this->assertEquals('settleaccount', $container->getSettleaccount());
        $this->assertEquals('txid', $container->getTxid());
    }

    /**
     * @return array
     */
    protected function getCaptureResponseParams()
    {
        return [
            'clearingamount' => 'clearingamount',
            'clearingbankaccount' => 'clearingbankaccount',
            'clearingbankaccountholder' => 'clearingbankaccountholder',
            'clearingbankbic' => 'clearingbankbic',
            'clearingbankcity' => 'clearingbankcity',
            'clearingbankcode' => 'clearingbankcode',
            'clearingbankcountry' => 'clearingbankcountry',
            'clearingbankiban' => 'clearingbankiban',
            'clearingbankname' => 'clearingbankname',
            'clearingdate' => 'clearingdate',
            'clearingduedate' => 'clearingduedate',
            'clearinginstructionnote' => 'clearinginstructionnote',
            'clearinglegalnote' => 'clearinglegalnote',
            'clearingreference' => 'clearingreference',

            'creditorcity' => 'creditorcity',
            'creditorcountry' => 'creditorcountry',
            'creditoremail' => 'creditoremail',
            'creditoridentifier' => 'creditoridentifier',
            'creditorname' => 'creditorname',
            'creditorstreet' => 'creditorstreet',
            'creditorzip' => 'creditorzip',

            'mandateidentification' => 'mandateidentification',
            'settleaccount' => 'settleaccount',
            'txid' => 'txid',
        ];
    }

    public function testDebitResponseContainer()
    {
        $params = array_merge($this->getStandardResponseParams(), $this->getDebitResponseParams());

        $container = new DebitResponseContainer($params);

        $this->assertInstanceOf('SprykerFeature\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer', $container);
        $this->assertStandardParams($container);
        $this->assertEquals('settleaccount', $container->getSettleaccount());
        $this->assertEquals('txid', $container->getTxid());
    }

    /**
     * @return array
     */
    protected function getDebitResponseParams()
    {
        return [
            'settleaccount' => 'settleaccount',
            'txid' => 'txid',
        ];
    }

    public function testRefundResponseContainer()
    {
        $params = array_merge($this->getStandardResponseParams(), $this->getRefundResponseParams());

        $container = new RefundResponseContainer($params);

        $this->assertInstanceOf('SprykerFeature\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer', $container);
        $this->assertStandardParams($container);
        $this->assertEquals('protectresultavs', $container->getProtectResultAvs());
        $this->assertEquals('txid', $container->getTxid());
    }

    /**
     * @return array
     */
    protected function getRefundResponseParams()
    {
        return [
            'protectresultavs' => 'protectresultavs',
            'txid' => 'txid',
        ];
    }

    /**
     * @return array
     */
    protected function getStandardResponseParams()
    {
        $params = [
            'customermessage' => 'customermessage',
            'errorcode' => 'errorcode',
            'errormessage' => 'errormessage',
            'rawResponse' => 'rawresponse',
            'status' => 'status',
        ];

        return $params;
    }

    /**
     * @param AbstractResponseContainer $container
     */
    protected function assertStandardParams(AbstractResponseContainer $container)
    {
        $this->assertEquals('customermessage', $container->getCustomermessage());
        $this->assertEquals('errorcode', $container->getErrorcode());
        $this->assertEquals('errormessage', $container->getErrormessage());
        $this->assertEquals('rawresponse', $container->getRawResponse());
        $this->assertEquals('status', $container->getStatus());
    }

}
