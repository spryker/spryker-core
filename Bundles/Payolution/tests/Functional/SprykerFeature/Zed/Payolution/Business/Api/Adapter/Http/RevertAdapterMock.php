<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;

class RevertAdapterMock extends AbstractAdapterMock
{

    /**
     * @return array
     */
    public function getSuccessResponse()
    {
        return [
            'P3_VALIDATION' => 'ACK',
            'IDENTIFICATION_SHOPPERID' => '789',
            'CLEARING_DESCRIPTOR' => '4259.5775.3506 inv-ins-test-default 794',
            'PROCESSING_CONNECTORDETAIL_ConnectorTxID1' => 'Tx-3p46gixkvk3',
            'TRANSACTION_CHANNEL' => '8a82941832d84c500132e875fc0c0648',
            'PROCESSING_REASON_CODE' => '00',
            'PROCESSING_CODE' => 'VA.RV.90.00',
            'FRONTEND_REQUEST_CANCELLED' => 'false',
            'PROCESSING_REASON' => 'Successful Processing',
            'FRONTEND_MODE' => 'DEFAULT',
            'CLEARING_FXSOURCE' => 'INTERN',
            'CLEARING_AMOUNT' => '100.00',
            'PROCESSING_RESULT' => 'ACK',
            'NAME_SALUTATION' => 'NONE',
            'PRESENTATION_USAGE' => '794',
            'POST_VALIDATION' => 'ACK',
            'CLEARING_CURRENCY' => 'EUR',
            'FRONTEND_SESSION_ID' => '',
            'PROCESSING_STATUS_CODE' => '90',
            'PRESENTATION_CURRENCY' => 'EUR',
            'PAYMENT_CODE' => 'VA.RV',
            'PROCESSING_RETURN_CODE' => '000.100.112',
            'CONTACT_IP' => '5.145.176.11',
            'IDENTIFICATION_REFERENCEID' => '8a82944a4fd6d7cd014fdf668c67314a',
            'PROCESSING_STATUS' => 'NEW',
            'FRONTEND_CC_LOGO' => 'images/visa_mc.gif',
            'PRESENTATION_AMOUNT' => '100.00',
            'IDENTIFICATION_UNIQUEID' => '8a82944a4fd6d7cd014fdf6a203e3858',
            'IDENTIFICATION_TRANSACTIONID' => 'tran_55fbc10dcf3ed',
            'IDENTIFICATION_SHORTID' => '4259.5775.3506',
            'CLEARING_FXRATE' => '1.0',
            'PROCESSING_TIMESTAMP' => '2015-09-18 07:45:18',
            'ADDRESS_COUNTRY' => 'DE',
            'PROCESSING_CONNECTORDETAIL_PaymentReference' => 'Tx-3p46gixkvk3',
            'RESPONSE_VERSION' => '1.0',
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
            'TRANSACTION_RESPONSE' => 'SYNC',
            'PROCESSING_RETURN' => 'Request successfully processed in \'Merchant in Connector Test Mode\'',
'CLEARING_FXDATE' => "2015-09-18 07:45:18\r\n",
        ];
    }

    /**
     * @return array
     */
    public function getFailureResponse()
    {
        return [
            'TRANSACTION_CHANNEL' => '8a82941832d84c500132e875fc0c0648',
            'PRESENTATION_CURRENCY' => 'EUR',
            'IDENTIFICATION_UNIQUEID' => '8a82944a4fd6d7cd014fdf834b276c0c',
            'PAYMENT_CODE' => 'VA.RV',
            'FRONTEND_CC_LOGO' => 'images/visa_mc.gif',
            'PROCESSING_STATUS' => 'REJECTED_VALIDATION',
            'CONTACT_IP' => '5.145.176.11',
            'FRONTEND_MODE' => 'DEFAULT',
            'FRONTEND_REQUEST_CANCELLED' => 'false',
            'PROCESSING_RETURN' => 'referenced session is REJECTED (no action possible).',
            'PROCESSING_REASON' => 'Registration Error',
            'PROCESSING_STATUS_CODE' => '70',
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
            'IDENTIFICATION_SHOPPERID' => '807',
            'POST_VALIDATION' => 'ACK',
            'PROCESSING_TIMESTAMP' => '2015-09-18 08:12:47',
            'PROCESSING_RETURN_CODE' => '100.350.100',
            'RESPONSE_VERSION' => '1.0',
            'IDENTIFICATION_REFERENCEID' => '8a82944a4fd6d7cd014fdf83352b6be1',
            'TRANSACTION_RESPONSE' => 'SYNC',
            'P3_VALIDATION' => 'ACK',
            'PROCESSING_CODE' => 'VA.RV.70.64',
            'FRONTEND_SESSION_ID' => '',
            'PROCESSING_REASON_CODE' => '64',
            'IDENTIFICATION_SHORTID' => '4690.4164.4194',
            'PRESENTATION_USAGE' => '812',
            'NAME_SALUTATION' => 'NONE',
            'PROCESSING_RESULT' => 'NOK',
            'IDENTIFICATION_TRANSACTIONID' => 'tran_55fbc77f6b102',
            'PRESENTATION_AMOUNT' => '10000.00',
            'ADDRESS_COUNTRY' => "DE\r\n",
        ];
    }

}
