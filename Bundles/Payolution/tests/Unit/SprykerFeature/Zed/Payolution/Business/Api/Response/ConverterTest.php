<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Payolution\Business\Api\Response;

use SprykerFeature\Zed\Payolution\Business\Api\Response\Converter;

class ConverterTest extends \PHPUnit_Framework_TestCase
{

    public function testFromArray()
    {
        $exporter = new Converter();
        $responseTransfer = $exporter->fromArray($this->getTestResponseData());
        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $responseTransfer);
        $this->assertEquals('DE', $responseTransfer->getAddressCountry());
        $this->assertEquals('Berlin', $responseTransfer->getAddressCity());
        $this->assertEquals('10623', $responseTransfer->getAddressZip());
        $this->assertEquals('Straße des 17. Juni 135', $responseTransfer->getAddressStreet());
    }

    /**
     * @return array
     */
    private function getTestResponseData()
    {
        return [
            'PROCESSING_RISK_SCORE' => '0',
            'P3_VALIDATION' => 'ACK',
            'NAME_GIVEN' => 'John',
            'IDENTIFICATION_SHOPPERID' => '158',
            'CLEARING_DESCRIPTOR' => '2214.7311.2738 inv-ins-test-default 179',
            'PROCESSING_CONNECTORDETAIL_ConnectorTxID1' => 'Tx-cgwebcjwuk4',
            'TRANSACTION_CHANNEL' => '8a82941832d84c500132e875fc0c0648',
            'PROCESSING_REASON_CODE' => '00',
            'ADDRESS_CITY' => 'Berlin',
            'FRONTEND_REQUEST_CANCELLED' => 'false',
            'PROCESSING_CODE' => 'VA.PA.90.00',
            'PROCESSING_REASON' => 'Successful Processing',
            'FRONTEND_MODE' => 'DEFAULT',
            'CLEARING_FXSOURCE' => 'INTERN',
            'CLEARING_AMOUNT' => '100.00',
            'PROCESSING_RESULT' => 'ACK',
            'NAME_SALUTATION' => 'MR',
            'PRESENTATION_USAGE' => '179',
            'POST_VALIDATION' => 'ACK',
            'CONTACT_EMAIL' => 'john@doe.com',
            'CLEARING_CURRENCY' => 'EUR',
            'FRONTEND_SESSION_ID' => '',
            'PROCESSING_STATUS_CODE' => '90',
            'PRESENTATION_CURRENCY' => 'EUR',
            'PAYMENT_CODE' => 'VA.PA',
            'NAME_BIRTHDATE' => '1970-01-01',
            'PROCESSING_RETURN_CODE' => '000.100.112',
            'CONTACT_IP' => '127.0.0.1',
            'NAME_FAMILY' => 'Doe',
            'PROCESSING_STATUS' => 'NEW',
            'FRONTEND_CC_LOGO' => 'images/visa_mc.gif',
            'PRESENTATION_AMOUNT' => '100.00',
            'IDENTIFICATION_UNIQUEID' => '8a82944a4fbc48bb014fbd1f3a544ace',
            'IDENTIFICATION_TRANSACTIONID' => 'tran_55f2f9a314ed4',
            'IDENTIFICATION_SHORTID' => '2214.7311.2738',
            'CLEARING_FXRATE' => '1.0',
            'PROCESSING_TIMESTAMP' => '2015-09-11 15:56:26',
            'ADDRESS_COUNTRY' => 'DE',
            'PROCESSING_CONNECTORDETAIL_PaymentReference' => 'RSRX-BWHY-JLDN',
            'RESPONSE_VERSION' => '1.0',
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
            'PROCESSING_RETURN' => 'Request successfully processed in \'Merchant in Connector Test Mode\'',
            'TRANSACTION_RESPONSE' => 'SYNC',
            'ADDRESS_STREET' => 'Straße des 17. Juni 135',
            'NAME_SEX' => 'M',
            'CLEARING_FXDATE' => '2015-09-11 15:56:24',
            'ADDRESS_ZIP' => '10623',
        ];
    }

}
