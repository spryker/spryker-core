<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Response;


class PreAuthorizationResponse extends AbstractResponse
{
    /**
     * @param string
     */
    protected $processing_risk_score;

    /**
     * @param string
     */
    protected $p3_validation;

    /**
     * @param string
     */
    protected $name_given;

    /**
     * @param string
     */
    protected $identification_shopperid;

    /**
     * @param string
     */
    protected $clearing_descriptor;

    /**
     * @param string
     */
    protected $processing_connectordetail_connectortxid1;

    /**
     * @param string
     */
    protected $transaction_channel;

    /**
     * @param string
     */
    protected $processing_reason_code;

    /**
     * @param string
     */
    protected $address_city;

    /**
     * @param string
     */
    protected $frontend_request_cancelled;

    /**
     * @param string
     */
    protected $processing_code;

    /**
     * @param string
     */
    protected $processing_reason;

    /**
     * @param string
     */
    protected $frontend_mode;

    /**
     * @param string
     */
    protected $clearing_fxsource;

    /**
     * @param string
     */
    protected $clearing_amount;

    /**
     * @param string
     */
    protected $processing_result;

    /**
     * @param string
     */
    protected $name_salutation;

    /**
     * @param string
     */
    protected $presentation_usage;

    /**
     * @param string
     */
    protected $post_validation;

    /**
     * @param string
     */
    protected $contact_email;

    /**
     * @param string
     */
    protected $clearing_currency;

    /**
     * @param string
     */
    protected $frontend_session_id;

    /**
     * @param string
     */
    protected $processing_status_code;

    /**
     * @param string
     */
    protected $presentation_currency;

    /**
     * @param string
     */
    protected $payment_code;

    /**
     * @param string
     */
    protected $name_birthdate;

    /**
     * @param string
     */
    protected $processing_return_code;

    /**
     * @param string
     */
    protected $contact_ip;

    /**
     * @param string
     */
    protected $name_family;

    /**
     * @param string
     */
    protected $processing_status;

    /**
     * @param string
     */
    protected $frontend_cc_logo;

    /**
     * @param string
     */
    protected $presentation_amount;

    /**
     * @param string
     */
    protected $identification_uniqueid;

    /**
     * @param string
     */
    protected $identification_transactionid;

    /**
     * @param string
     */
    protected $identification_shortid;

    /**
     * @param string
     */
    protected $clearing_fxrate;

    /**
     * @param string
     */
    protected $processing_timestamp;

    /**
     * @param string
     */
    protected $address_country;

    /**
     * @param string
     */
    protected $processing_connectordetail_paymentreference;

    /**
     * @param string
     */
    protected $response_version;

    /**
     * @param string
     */
    protected $transaction_mode;

    /**
     * @param string
     */
    protected $processing_return;

    /**
     * @param string
     */
    protected $transaction_response;

    /**
     * @param string
     */
    protected $address_street;

    /**
     * @param string
     */
    protected $name_sex;

    /**
     * @param string
     */
    protected $clearing_fxdate;

    /**
     * @param string
     */
    protected $address_zip;

    /**
     * @return mixed
     */
    public function getProcessingRiskScore()
    {
        return $this->processing_risk_score;
    }

    /**
     * @param mixed $processing_risk_score
     *
     * @return $this
     */
    public function setProcessingRiskScore($processing_risk_score)
    {
        $this->processing_risk_score = $processing_risk_score;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getP3Validation()
    {
        return $this->p3_validation;
    }

    /**
     * @param mixed $p3_validation
     *
     * @return $this
     */
    public function setP3Validation($p3_validation)
    {
        $this->p3_validation = $p3_validation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNameGiven()
    {
        return $this->name_given;
    }

    /**
     * @param mixed $name_given
     *
     * @return $this
     */
    public function setNameGiven($name_given)
    {
        $this->name_given = $name_given;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentificationShopperid()
    {
        return $this->identification_shopperid;
    }

    /**
     * @param mixed $identification_shopperid
     *
     * @return $this
     */
    public function setIdentificationShopperid($identification_shopperid)
    {
        $this->identification_shopperid = $identification_shopperid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClearingDescriptor()
    {
        return $this->clearing_descriptor;
    }

    /**
     * @param mixed $clearing_descriptor
     *
     * @return $this
     */
    public function setClearingDescriptor($clearing_descriptor)
    {
        $this->clearing_descriptor = $clearing_descriptor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingConnectordetailConnectortxid1()
    {
        return $this->processing_connectordetail_connectortxid1;
    }

    /**
     * @param mixed $processing_connectordetail_connectortxid1
     *
     * @return $this
     */
    public function setProcessingConnectordetailConnectortxid1($processing_connectordetail_connectortxid1)
    {
        $this->processing_connectordetail_connectortxid1 = $processing_connectordetail_connectortxid1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactionChannel()
    {
        return $this->transaction_channel;
    }

    /**
     * @param mixed $transaction_channel
     *
     * @return $this
     */
    public function setTransactionChannel($transaction_channel)
    {
        $this->transaction_channel = $transaction_channel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingReasonCode()
    {
        return $this->processing_reason_code;
    }

    /**
     * @param mixed $processing_reason_code
     *
     * @return $this
     */
    public function setProcessingReasonCode($processing_reason_code)
    {
        $this->processing_reason_code = $processing_reason_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressCity()
    {
        return $this->address_city;
    }

    /**
     * @param mixed $address_city
     *
     * @return $this
     */
    public function setAddressCity($address_city)
    {
        $this->address_city = $address_city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrontendRequestCancelled()
    {
        return $this->frontend_request_cancelled;
    }

    /**
     * @param mixed $frontend_request_cancelled
     *
     * @return $this
     */
    public function setFrontendRequestCancelled($frontend_request_cancelled)
    {
        $this->frontend_request_cancelled = $frontend_request_cancelled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingCode()
    {
        return $this->processing_code;
    }

    /**
     * @param mixed $processing_code
     *
     * @return $this
     */
    public function setProcessingCode($processing_code)
    {
        $this->processing_code = $processing_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingReason()
    {
        return $this->processing_reason;
    }

    /**
     * @param mixed $processing_reason
     *
     * @return $this
     */
    public function setProcessingReason($processing_reason)
    {
        $this->processing_reason = $processing_reason;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrontendMode()
    {
        return $this->frontend_mode;
    }

    /**
     * @param mixed $frontend_mode
     *
     * @return $this
     */
    public function setFrontendMode($frontend_mode)
    {
        $this->frontend_mode = $frontend_mode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClearingFxsource()
    {
        return $this->clearing_fxsource;
    }

    /**
     * @param mixed $clearing_fxsource
     *
     * @return $this
     */
    public function setClearingFxsource($clearing_fxsource)
    {
        $this->clearing_fxsource = $clearing_fxsource;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClearingAmount()
    {
        return $this->clearing_amount;
    }

    /**
     * @param mixed $clearing_amount
     *
     * @return $this
     */
    public function setClearingAmount($clearing_amount)
    {
        $this->clearing_amount = $clearing_amount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingResult()
    {
        return $this->processing_result;
    }

    /**
     * @param mixed $processing_result
     *
     * @return $this
     */
    public function setProcessingResult($processing_result)
    {
        $this->processing_result = $processing_result;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNameSalutation()
    {
        return $this->name_salutation;
    }

    /**
     * @param mixed $name_salutation
     *
     * @return $this
     */
    public function setNameSalutation($name_salutation)
    {
        $this->name_salutation = $name_salutation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPresentationUsage()
    {
        return $this->presentation_usage;
    }

    /**
     * @param mixed $presentation_usage
     *
     * @return $this
     */
    public function setPresentationUsage($presentation_usage)
    {
        $this->presentation_usage = $presentation_usage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostValidation()
    {
        return $this->post_validation;
    }

    /**
     * @param mixed $post_validation
     *
     * @return $this
     */
    public function setPostValidation($post_validation)
    {
        $this->post_validation = $post_validation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContactEmail()
    {
        return $this->contact_email;
    }

    /**
     * @param mixed $contact_email
     *
     * @return $this
     */
    public function setContactEmail($contact_email)
    {
        $this->contact_email = $contact_email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClearingCurrency()
    {
        return $this->clearing_currency;
    }

    /**
     * @param mixed $clearing_currency
     *
     * @return $this
     */
    public function setClearingCurrency($clearing_currency)
    {
        $this->clearing_currency = $clearing_currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrontendSessionId()
    {
        return $this->frontend_session_id;
    }

    /**
     * @param mixed $frontend_session_id
     *
     * @return $this
     */
    public function setFrontendSessionId($frontend_session_id)
    {
        $this->frontend_session_id = $frontend_session_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingStatusCode()
    {
        return $this->processing_status_code;
    }

    /**
     * @param mixed $processing_status_code
     *
     * @return $this
     */
    public function setProcessingStatusCode($processing_status_code)
    {
        $this->processing_status_code = $processing_status_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPresentationCurrency()
    {
        return $this->presentation_currency;
    }

    /**
     * @param mixed $presentation_currency
     *
     * @return $this
     */
    public function setPresentationCurrency($presentation_currency)
    {
        $this->presentation_currency = $presentation_currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentCode()
    {
        return $this->payment_code;
    }

    /**
     * @param mixed $payment_code
     *
     * @return $this
     */
    public function setPaymentCode($payment_code)
    {
        $this->payment_code = $payment_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNameBirthdate()
    {
        return $this->name_birthdate;
    }

    /**
     * @param mixed $name_birthdate
     *
     * @return $this
     */
    public function setNameBirthdate($name_birthdate)
    {
        $this->name_birthdate = $name_birthdate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingReturnCode()
    {
        return $this->processing_return_code;
    }

    /**
     * @param mixed $processing_return_code
     *
     * @return $this
     */
    public function setProcessingReturnCode($processing_return_code)
    {
        $this->processing_return_code = $processing_return_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContactIp()
    {
        return $this->contact_ip;
    }

    /**
     * @param mixed $contact_ip
     *
     * @return $this
     */
    public function setContactIp($contact_ip)
    {
        $this->contact_ip = $contact_ip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNameFamily()
    {
        return $this->name_family;
    }

    /**
     * @param mixed $name_family
     *
     * @return $this
     */
    public function setNameFamily($name_family)
    {
        $this->name_family = $name_family;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingStatus()
    {
        return $this->processing_status;
    }

    /**
     * @param mixed $processing_status
     *
     * @return $this
     */
    public function setProcessingStatus($processing_status)
    {
        $this->processing_status = $processing_status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrontendCcLogo()
    {
        return $this->frontend_cc_logo;
    }

    /**
     * @param mixed $frontend_cc_logo
     *
     * @return $this
     */
    public function setFrontendCcLogo($frontend_cc_logo)
    {
        $this->frontend_cc_logo = $frontend_cc_logo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPresentationAmount()
    {
        return $this->presentation_amount;
    }

    /**
     * @param mixed $presentation_amount
     *
     * @return $this
     */
    public function setPresentationAmount($presentation_amount)
    {
        $this->presentation_amount = $presentation_amount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentificationUniqueid()
    {
        return $this->identification_uniqueid;
    }

    /**
     * @param mixed $identification_uniqueid
     *
     * @return $this
     */
    public function setIdentificationUniqueid($identification_uniqueid)
    {
        $this->identification_uniqueid = $identification_uniqueid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentificationTransactionid()
    {
        return $this->identification_transactionid;
    }

    /**
     * @param mixed $identification_transactionid
     *
     * @return $this
     */
    public function setIdentificationTransactionid($identification_transactionid)
    {
        $this->identification_transactionid = $identification_transactionid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentificationShortid()
    {
        return $this->identification_shortid;
    }

    /**
     * @param mixed $identification_shortid
     *
     * @return $this
     */
    public function setIdentificationShortid($identification_shortid)
    {
        $this->identification_shortid = $identification_shortid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClearingFxrate()
    {
        return $this->clearing_fxrate;
    }

    /**
     * @param mixed $clearing_fxrate
     *
     * @return $this
     */
    public function setClearingFxrate($clearing_fxrate)
    {
        $this->clearing_fxrate = $clearing_fxrate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingTimestamp()
    {
        return $this->processing_timestamp;
    }

    /**
     * @param mixed $processing_timestamp
     *
     * @return $this
     */
    public function setProcessingTimestamp($processing_timestamp)
    {
        $this->processing_timestamp = $processing_timestamp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressCountry()
    {
        return $this->address_country;
    }

    /**
     * @param mixed $address_country
     *
     * @return $this
     */
    public function setAddressCountry($address_country)
    {
        $this->address_country = $address_country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingConnectordetailPaymentreference()
    {
        return $this->processing_connectordetail_paymentreference;
    }

    /**
     * @param mixed $processing_connectordetail_paymentreference
     *
     * @return $this
     */
    public function setProcessingConnectordetailPaymentreference($processing_connectordetail_paymentreference)
    {
        $this->processing_connectordetail_paymentreference = $processing_connectordetail_paymentreference;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponseVersion()
    {
        return $this->response_version;
    }

    /**
     * @param mixed $response_version
     *
     * @return $this
     */
    public function setResponseVersion($response_version)
    {
        $this->response_version = $response_version;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactionMode()
    {
        return $this->transaction_mode;
    }

    /**
     * @param mixed $transaction_mode
     *
     * @return $this
     */
    public function setTransactionMode($transaction_mode)
    {
        $this->transaction_mode = $transaction_mode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcessingReturn()
    {
        return $this->processing_return;
    }

    /**
     * @param mixed $processing_return
     *
     * @return $this
     */
    public function setProcessingReturn($processing_return)
    {
        $this->processing_return = $processing_return;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactionResponse()
    {
        return $this->transaction_response;
    }

    /**
     * @param mixed $transaction_response
     *
     * @return $this
     */
    public function setTransactionResponse($transaction_response)
    {
        $this->transaction_response = $transaction_response;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressStreet()
    {
        return $this->address_street;
    }

    /**
     * @param mixed $address_street
     *
     * @return $this
     */
    public function setAddressStreet($address_street)
    {
        $this->address_street = $address_street;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNameSex()
    {
        return $this->name_sex;
    }

    /**
     * @param mixed $name_sex
     *
     * @return $this
     */
    public function setNameSex($name_sex)
    {
        $this->name_sex = $name_sex;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClearingFxdate()
    {
        return $this->clearing_fxdate;
    }

    /**
     * @param mixed $clearing_fxdate
     *
     * @return $this
     */
    public function setClearingFxdate($clearing_fxdate)
    {
        $this->clearing_fxdate = $clearing_fxdate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressZip()
    {
        return $this->address_zip;
    }

    /**
     * @param mixed $address_zip
     *
     * @return $this
     */
    public function setAddressZip($address_zip)
    {
        $this->address_zip = $address_zip;
        return $this;
    }

}
