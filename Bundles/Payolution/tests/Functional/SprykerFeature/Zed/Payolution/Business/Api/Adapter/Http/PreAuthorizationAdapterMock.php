<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;

class PreAuthorizationAdapterMock extends AbstractAdapterMock
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
            // We need to set the request's transaction id to fulfil the foreign-key constraint
            'IDENTIFICATION_TRANSACTIONID' => $this->requestData['IDENTIFICATION.TRANSACTIONID'],
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

    /**
     * @return array
     */
    public function getFailureResponse()
    {
        return [
            'processing_risk_score' => '0',
            'p3_validation' => 'ACK',
            'name_given' => 'Jane',
            'identification_shopperid' => '125',
            'clearing_descriptor' => '4485.8392.6434 inv-ins-test-default 108',
            'processing_connectordetail_connectortxid1' => 'Tx-vpp5d9kzx8w',
            'transaction_channel' => '8a82941832d84c500132e875fc0c0648',
            'processing_reason_code' => '95',
            'address_city' => 'DE',
            'frontend_request_cancelled' => 'false',
            'processing_code' => 'VA.PA.60.95',
            'processing_reason' => 'Authorization Error',
            'frontend_mode' => 'DEFAULT',
            'clearing_fxsource' => 'INTERN',
            'clearing_amount' => '100.00',
            'processing_result' => 'NOK',
            'name_salutation' => 'MR',
            'presentation_usage' => '108',
            'post_validation' => 'ACK',
            'contact_email' => 'jane@family-doe.org',
            'clearing_currency' => 'EUR',
            'frontend_session_id' => '',
            'processing_status_code' => '60',
            'presentation_currency' => 'EUR',
            'payment_code' => 'VA.PA',
            'name_birthdate' => '1970-01-02',
            'processing_return_code' => '800.100.170',
            'contact_ip' => '127.0.0.1',
            'name_family' => 'Doe',
            'processing_status' => 'REJECTED_BANK',
            'frontend_cc_logo' => 'images/visa_mc.gif',
            'presentation_amount' => '100.00',
            'identification_uniqueid' => '8a8294494fd0cad9014fd1388b433e85',
            // We need to set the request's transaction id to fulfil the foreign-key constraint
            'identification_transactionid' => $this->requestData['IDENTIFICATION.TRANSACTIONID'],
            'identification_shortid' => '4485.8392.6434',
            'identification_referenceid' => '',
            'clearing_fxrate' => '1.0',
            'processing_timestamp' => '2015-09-15 13:36:29',
            'address_country' => 'DE',
            'processing_connectordetail_paymentreference' => 'LWML-CSSP-KKPW',
            'response_version' => '1.0',
            'transaction_mode' => 'CONNECTOR_TEST',
            'processing_return' => 'transaction declined (transaction not permitted)',
            'transaction_response' => 'SYNC',
            'address_street' => 'Straße des 17. Juni 135',
            'name_sex' => 'M',
            'clearing_fxdate' => '2015-09-15 13:36:28',
            'address_zip' => '10623',
            'name_title' => 'Mr',
        ];
    }

}
