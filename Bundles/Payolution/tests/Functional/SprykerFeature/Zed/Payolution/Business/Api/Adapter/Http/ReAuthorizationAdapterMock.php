<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;


class ReAuthorizationAdapterMock extends AbstractAdapterMock
{

    /**
     * @return array
     */
    public function getSuccessResponse()
    {
        return [
            'PROCESSING_RISK_SCORE' => '0',
            'P3_VALIDATION' => 'ACK',
            'IDENTIFICATION_SHOPPERID' => '1279',
            'CLEARING_DESCRIPTOR' => '1916.6340.7778 inv-ins-test-default 1284',
            'PROCESSING_CONNECTORDETAIL_ConnectorTxID1' => 'Tx-5znpvuspnsz',
            'TRANSACTION_CHANNEL' => '8a82941832d84c500132e875fc0c0648',
            'PROCESSING_REASON_CODE' => '00',
            'PROCESSING_CODE' => 'VA.PA.90.00',
            'FRONTEND_REQUEST_CANCELLED' => 'false',
            'PROCESSING_REASON' => 'Successful Processing',
            'FRONTEND_MODE' => 'DEFAULT',
            'CLEARING_FXSOURCE' => 'INTERN',
            'CLEARING_AMOUNT' => '200.00',
            'PROCESSING_RESULT' => 'ACK',
            'NAME_SALUTATION' => 'NONE',
            'PRESENTATION_USAGE' => '1284',
            'POST_VALIDATION' => 'ACK',
            'CLEARING_CURRENCY' => 'EUR',
            'FRONTEND_SESSION_ID' => '',
            'PROCESSING_STATUS_CODE' => '90',
            'PRESENTATION_CURRENCY' => 'EUR',
            'PAYMENT_CODE' => 'VA.PA',
            'PROCESSING_RETURN_CODE' => '000.100.112',
            'CONTACT_IP' => '5.145.176.11',
            'IDENTIFICATION_REFERENCEID' => $this->requestData['IDENTIFICATION.REFERENCEID'],
            'PROCESSING_STATUS' => 'NEW',
            'FRONTEND_CC_LOGO' => 'images/visa_mc.gif',
            'PRESENTATION_AMOUNT' => '200.00',
            'IDENTIFICATION_UNIQUEID' => '8a82944a4fe06456014feefc67fb0332',
            // We need to set the request's transaction id to fulfil the foreign-key constraint
            'IDENTIFICATION_TRANSACTIONID' => $this->requestData['IDENTIFICATION.TRANSACTIONID'],
            'IDENTIFICATION_SHORTID' => '1916.6340.7778',
            'CLEARING_FXRATE' => '1.0',
            'PROCESSING_TIMESTAMP' => '2015-09-21 08:19:53',
            'ADDRESS_COUNTRY' => 'DE',
            'PROCESSING_CONNECTORDETAIL_PaymentReference' => 'BCTQ-DDGZ-MVKB',
            'RESPONSE_VERSION' => '1.0',
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
            'TRANSACTION_RESPONSE' => 'SYNC',
            'PROCESSING_RETURN' => 'Request successfully processed in \'Merchant in Connector Test Mode\'',
            'CLEARING_FXDATE' => "2015-09-21 08:19:51\r\n",
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
            'IDENTIFICATION_UNIQUEID' => '8a82944a4fe06456014fef00b16f0ea1',
            'PAYMENT_CODE' => 'VA.PA',
            'FRONTEND_CC_LOGO' => 'images/visa_mc.gif',
            'PROCESSING_STATUS' => 'REJECTED_RISK',
            'CONTACT_IP' => '5.145.176.11',
            'FRONTEND_MODE' => 'DEFAULT',
            'FRONTEND_REQUEST_CANCELLED' => 'false',
            'PROCESSING_RETURN' => 'Amount is outside allowed ticket size boundaries',
            'PROCESSING_REASON' => 'Amount Validation',
            'PROCESSING_STATUS_CODE' => '65',
            'TRANSACTION_MODE' => 'CONNECTOR_TEST',
            'IDENTIFICATION_SHOPPERID' => '1286',
            'POST_VALIDATION' => 'ACK',
            'PROCESSING_TIMESTAMP' => '2015-09-21 08:24:10',
            'PROCESSING_RETURN_CODE' => '100.550.312',
            'RESPONSE_VERSION' => '1.0',
            'IDENTIFICATION_REFERENCEID' => $this->requestData['IDENTIFICATION.REFERENCEID'],
            'TRANSACTION_RESPONSE' => 'SYNC',
            'P3_VALIDATION' => 'ACK',
            'PROCESSING_CODE' => 'VA.PA.65.35',
            'FRONTEND_SESSION_ID' => '',
            'PROCESSING_REASON_CODE' => '35',
            'PROCESSING_RISK_SCORE' => '-100',
            'IDENTIFICATION_SHORTID' => '1116.6964.1890',
            'PRESENTATION_USAGE' => '1291',
            'NAME_SALUTATION' => 'NONE',
            'PROCESSING_RESULT' => 'NOK',
            // We need to set the request's transaction id to fulfil the foreign-key constraint
            'identification_transactionid' => $this->requestData['IDENTIFICATION.TRANSACTIONID'],
            'PRESENTATION_AMOUNT' => '20000.00',
            'ADDRESS_COUNTRY' => "DE\r\n",
        ];
    }
}
