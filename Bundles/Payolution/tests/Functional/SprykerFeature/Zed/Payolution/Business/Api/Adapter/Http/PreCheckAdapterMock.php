<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;

class PreCheckAdapterMock extends AbstractAdapterMock
{

    /**
     * @return array
     */
    public function getSuccessResponse()
    {
        return [
            'PROCESSING_RISK_SCORE' => '0',
            'P3_VALIDATION' => 'ACK',
            'NAME_GIVEN' => 'John',
            'CLEARING_DESCRIPTOR' => '5066.2051.0882 inv-ins-test-default 668',
            'PROCESSING_CONNECTORDETAIL_ConnectorTxID1' => 'Tx-bp9g8kzy42m',
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
            'PRESENTATION_USAGE' => '668',
            'POST_VALIDATION' => 'ACK',
            'CONTACT_EMAIL' => 'john@doe.com',
            'CLEARING_CURRENCY' => 'EUR',
            'FRONTEND_SESSION_ID' => '',
            'CRITERION_PAYOLUTION_PRE_CHECK' => 'TRUE',
            'PROCESSING_STATUS_CODE' => '90',
            'NAME_TITLE' => 'Mr',
            'PRESENTATION_CURRENCY' => 'EUR',
            'PAYMENT_CODE' => 'VA.PA',
            'NAME_BIRTHDATE' => '1970-01-01',
            'PROCESSING_RETURN_CODE' => '000.100.112',
            'CONTACT_IP' => '127.0.0.1',
            'NAME_FAMILY' => 'Doe',
            'PROCESSING_STATUS' => 'NEW',
            'FRONTEND_CC_LOGO' => 'images/visa_mc.gif',
            'PRESENTATION_AMOUNT' => '100.00',
            'IDENTIFICATION_UNIQUEID' => '8a8294494fd6cc31014fdbf9a9897184',
            'IDENTIFICATION_TRANSACTIONID' => 'tran_55fade6b45b04',
            'IDENTIFICATION_SHORTID' => '5066.2051.0882',
            'CLEARING_FXRATE' => '1.0',
            'PROCESSING_TIMESTAMP' => '2015-09-17 15:43:38',
            'ADDRESS_COUNTRY' => 'de',
            'PROCESSING_CONNECTORDETAIL_PaymentReference' => 'JHLB-YJDF-RNDR',
            'RESPONSE_VERSION' => '1.0',
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
            'PROCESSING_RETURN' => 'Request successfully processed in \'Merchant in Connector Test Mode\'',
            'TRANSACTION_RESPONSE' => 'SYNC',
            'ADDRESS_STREET' => 'Straße des 17. Juni 135',
            'NAME_SEX' => 'M',
            'CLEARING_FXDATE' => '2015-09-17 15:43:36',
            'ADDRESS_ZIP' => "10623\r\n",
        ];
    }

    /**
     * @return array
     */
    public function getFailureResponse()
    {
        return [
            'PROCESSING_RISK_SCORE' => '-100',
            'P3_VALIDATION' => 'ACK',
            'NAME_GIVEN' => 'John',
            'TRANSACTION_CHANNEL' => '8a82941832d84c500132e875fc0c0648',
            'PROCESSING_REASON_CODE' => '35',
            'ADDRESS_CITY' => 'Berlin',
            'PROCESSING_CODE' => 'VA.PA.65.35',
            'FRONTEND_REQUEST_CANCELLED' => 'false',
            'PROCESSING_REASON' => 'Amount Validation',
            'FRONTEND_MODE' => 'DEFAULT',
            'PROCESSING_RESULT' => 'NOK',
            'NAME_SALUTATION' => 'MR',
            'PRESENTATION_USAGE' => '670',
            'POST_VALIDATION' => 'ACK',
            'CONTACT_EMAIL' => 'john@doe.com',
            'FRONTEND_SESSION_ID' => '',
            'CRITERION_PAYOLUTION_PRE_CHECK' => 'TRUE',
            'PROCESSING_STATUS_CODE' => '65',
            'NAME_TITLE' => 'Mr',
            'PRESENTATION_CURRENCY' => 'EUR',
            'PAYMENT_CODE' => 'VA.PA',
            'NAME_BIRTHDATE' => '1970-01-01',
            'PROCESSING_RETURN_CODE' => '100.550.312',
            'CONTACT_IP' => '127.0.0.1',
            'NAME_FAMILY' => 'Doe',
            'PROCESSING_STATUS' => 'REJECTED_RISK',
            'FRONTEND_CC_LOGO' => 'images/visa_mc.gif',
            'PRESENTATION_AMOUNT' => '100000.00',
            'IDENTIFICATION_UNIQUEID' => '8a82944a4fd6d7cd014fdbfbbb5e6439',
            'IDENTIFICATION_TRANSACTIONID' => 'tran_55fadef2eab5e',
            'IDENTIFICATION_SHORTID' => '9425.5969.1426',
            'PROCESSING_TIMESTAMP' => '2015-09-17 15:45:51',
            'ADDRESS_COUNTRY' => 'de',
            'RESPONSE_VERSION' => '1.0',
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
            'TRANSACTION_RESPONSE' => 'SYNC',
            'PROCESSING_RETURN' => 'Amount is outside allowed ticket size boundaries',
            'ADDRESS_STREET' => 'Straße des 17. Juni 135',
            'NAME_SEX' => 'M',
            'ADDRESS_ZIP' => "10623\r\n",
        ];
    }

}
