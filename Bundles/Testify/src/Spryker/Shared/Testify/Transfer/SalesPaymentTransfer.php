<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class SalesPaymentTransfer extends AbstractTransfer
{

    const METHOD = 'method';

    const REDIRECT_URL = 'redirectUrl';

    const CC_TYPE = 'ccType';

    const CC_NUMBER = 'ccNumber';

    const CC_CARDHOLDER = 'ccCardholder';

    const CC_EXPIRATION_MONTH = 'ccExpirationMonth';

    const CC_EXPIRATION_YEAR = 'ccExpirationYear';

    const CC_VERIFICATION = 'ccVerification';

    const DEBIT_HOLDER = 'debitHolder';

    const DEBIT_ACCOUNT_NUMBER = 'debitAccountNumber';

    const DEBIT_BANK_CODE_NUMBER = 'debitBankCodeNumber';

    const DEBIT_INSTITUTE = 'debitInstitute';

    const PSEUDO_CC_NUMBER = 'pseudoCcNumber';

    const PAYMENT_DATA = 'paymentData';

    const PAYMENT_DATA_CLASS_NAME = 'paymentDataClassName';

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $redirectUrl;

    /**
     * @var string
     */
    protected $ccType;

    /**
     * @var string
     */
    protected $ccNumber;

    /**
     * @var string
     */
    protected $ccCardholder;

    /**
     * @var string
     */
    protected $ccExpirationMonth;

    /**
     * @var string
     */
    protected $ccExpirationYear;

    /**
     * @var string
     */
    protected $ccVerification;

    /**
     * @var string
     */
    protected $debitHolder;

    /**
     * @var string
     */
    protected $debitAccountNumber;

    /**
     * @var string
     */
    protected $debitBankCodeNumber;

    /**
     * @var string
     */
    protected $debitInstitute;

    /**
     * @var string
     */
    protected $pseudoCcNumber;

    /**
     * @var string
     */
    protected $paymentData;

    /**
     * @var string
     */
    protected $paymentDataClassName;

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::METHOD => [
            'type' => 'string',
            'name_underscore' => 'method',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::REDIRECT_URL => [
            'type' => 'string',
            'name_underscore' => 'redirect_url',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::CC_TYPE => [
            'type' => 'string',
            'name_underscore' => 'cc_type',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::CC_NUMBER => [
            'type' => 'string',
            'name_underscore' => 'cc_number',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::CC_CARDHOLDER => [
            'type' => 'string',
            'name_underscore' => 'cc_cardholder',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::CC_EXPIRATION_MONTH => [
            'type' => 'string',
            'name_underscore' => 'cc_expiration_month',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::CC_EXPIRATION_YEAR => [
            'type' => 'string',
            'name_underscore' => 'cc_expiration_year',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::CC_VERIFICATION => [
            'type' => 'string',
            'name_underscore' => 'cc_verification',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::DEBIT_HOLDER => [
            'type' => 'string',
            'name_underscore' => 'debit_holder',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::DEBIT_ACCOUNT_NUMBER => [
            'type' => 'string',
            'name_underscore' => 'debit_account_number',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::DEBIT_BANK_CODE_NUMBER => [
            'type' => 'string',
            'name_underscore' => 'debit_bank_code_number',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::DEBIT_INSTITUTE => [
            'type' => 'string',
            'name_underscore' => 'debit_institute',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::PSEUDO_CC_NUMBER => [
            'type' => 'string',
            'name_underscore' => 'pseudo_cc_number',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::PAYMENT_DATA => [
            'type' => 'string',
            'name_underscore' => 'payment_data',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::PAYMENT_DATA_CLASS_NAME => [
            'type' => 'string',
            'name_underscore' => 'payment_data_class_name',
            'is_collection' => false,
            'is_transfer' => false,
        ],
    ];

    /**
     * @bundle Sales
     *
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        $this->addModifiedProperty(self::METHOD);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireMethod()
    {
        $this->assertPropertyIsSet(self::METHOD);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $redirectUrl
     *
     * @return $this
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        $this->addModifiedProperty(self::REDIRECT_URL);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireRedirectUrl()
    {
        $this->assertPropertyIsSet(self::REDIRECT_URL);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $ccType
     *
     * @return $this
     */
    public function setCcType($ccType)
    {
        $this->ccType = $ccType;
        $this->addModifiedProperty(self::CC_TYPE);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getCcType()
    {
        return $this->ccType;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireCcType()
    {
        $this->assertPropertyIsSet(self::CC_TYPE);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $ccNumber
     *
     * @return $this
     */
    public function setCcNumber($ccNumber)
    {
        $this->ccNumber = $ccNumber;
        $this->addModifiedProperty(self::CC_NUMBER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getCcNumber()
    {
        return $this->ccNumber;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireCcNumber()
    {
        $this->assertPropertyIsSet(self::CC_NUMBER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $ccCardholder
     *
     * @return $this
     */
    public function setCcCardholder($ccCardholder)
    {
        $this->ccCardholder = $ccCardholder;
        $this->addModifiedProperty(self::CC_CARDHOLDER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getCcCardholder()
    {
        return $this->ccCardholder;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireCcCardholder()
    {
        $this->assertPropertyIsSet(self::CC_CARDHOLDER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $ccExpirationMonth
     *
     * @return $this
     */
    public function setCcExpirationMonth($ccExpirationMonth)
    {
        $this->ccExpirationMonth = $ccExpirationMonth;
        $this->addModifiedProperty(self::CC_EXPIRATION_MONTH);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getCcExpirationMonth()
    {
        return $this->ccExpirationMonth;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireCcExpirationMonth()
    {
        $this->assertPropertyIsSet(self::CC_EXPIRATION_MONTH);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $ccExpirationYear
     *
     * @return $this
     */
    public function setCcExpirationYear($ccExpirationYear)
    {
        $this->ccExpirationYear = $ccExpirationYear;
        $this->addModifiedProperty(self::CC_EXPIRATION_YEAR);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getCcExpirationYear()
    {
        return $this->ccExpirationYear;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireCcExpirationYear()
    {
        $this->assertPropertyIsSet(self::CC_EXPIRATION_YEAR);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $ccVerification
     *
     * @return $this
     */
    public function setCcVerification($ccVerification)
    {
        $this->ccVerification = $ccVerification;
        $this->addModifiedProperty(self::CC_VERIFICATION);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getCcVerification()
    {
        return $this->ccVerification;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireCcVerification()
    {
        $this->assertPropertyIsSet(self::CC_VERIFICATION);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $debitHolder
     *
     * @return $this
     */
    public function setDebitHolder($debitHolder)
    {
        $this->debitHolder = $debitHolder;
        $this->addModifiedProperty(self::DEBIT_HOLDER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getDebitHolder()
    {
        return $this->debitHolder;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireDebitHolder()
    {
        $this->assertPropertyIsSet(self::DEBIT_HOLDER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $debitAccountNumber
     *
     * @return $this
     */
    public function setDebitAccountNumber($debitAccountNumber)
    {
        $this->debitAccountNumber = $debitAccountNumber;
        $this->addModifiedProperty(self::DEBIT_ACCOUNT_NUMBER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getDebitAccountNumber()
    {
        return $this->debitAccountNumber;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireDebitAccountNumber()
    {
        $this->assertPropertyIsSet(self::DEBIT_ACCOUNT_NUMBER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $debitBankCodeNumber
     *
     * @return $this
     */
    public function setDebitBankCodeNumber($debitBankCodeNumber)
    {
        $this->debitBankCodeNumber = $debitBankCodeNumber;
        $this->addModifiedProperty(self::DEBIT_BANK_CODE_NUMBER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getDebitBankCodeNumber()
    {
        return $this->debitBankCodeNumber;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireDebitBankCodeNumber()
    {
        $this->assertPropertyIsSet(self::DEBIT_BANK_CODE_NUMBER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $debitInstitute
     *
     * @return $this
     */
    public function setDebitInstitute($debitInstitute)
    {
        $this->debitInstitute = $debitInstitute;
        $this->addModifiedProperty(self::DEBIT_INSTITUTE);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getDebitInstitute()
    {
        return $this->debitInstitute;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireDebitInstitute()
    {
        $this->assertPropertyIsSet(self::DEBIT_INSTITUTE);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $pseudoCcNumber
     *
     * @return $this
     */
    public function setPseudoCcNumber($pseudoCcNumber)
    {
        $this->pseudoCcNumber = $pseudoCcNumber;
        $this->addModifiedProperty(self::PSEUDO_CC_NUMBER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getPseudoCcNumber()
    {
        return $this->pseudoCcNumber;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePseudoCcNumber()
    {
        $this->assertPropertyIsSet(self::PSEUDO_CC_NUMBER);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $paymentData
     *
     * @return $this
     */
    public function setPaymentData($paymentData)
    {
        $this->paymentData = $paymentData;
        $this->addModifiedProperty(self::PAYMENT_DATA);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getPaymentData()
    {
        return $this->paymentData;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePaymentData()
    {
        $this->assertPropertyIsSet(self::PAYMENT_DATA);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @param string $paymentDataClassName
     *
     * @return $this
     */
    public function setPaymentDataClassName($paymentDataClassName)
    {
        $this->paymentDataClassName = $paymentDataClassName;
        $this->addModifiedProperty(self::PAYMENT_DATA_CLASS_NAME);

        return $this;
    }

    /**
     * @bundle Sales
     *
     * @return string
     */
    public function getPaymentDataClassName()
    {
        return $this->paymentDataClassName;
    }

    /**
     * @bundle Sales
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePaymentDataClassName()
    {
        $this->assertPropertyIsSet(self::PAYMENT_DATA_CLASS_NAME);

        return $this;
    }

}
