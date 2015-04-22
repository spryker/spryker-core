<?php 

namespace SprykerFeature\Shared\Sales\Transfer;

/**
 *
 */
class Payment extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $method = null;

    protected $redirectUrl = null;

    protected $ccType = null;

    protected $ccNumber = null;

    protected $ccCardholder = null;

    protected $ccExpirationMonth = null;

    protected $ccExpirationYear = null;

    protected $ccVerification = null;

    protected $debitHolder = null;

    protected $debitAccountNumber = null;

    protected $debitBankCodeNumber = null;

    protected $debitInstitute = null;

    protected $pseudoCcNumber = null;

    protected $paymentData = null;

    protected $paymentData_ClassName = null;

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        $this->addModifiedProperty('method');
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $redirectUrl
     * @return $this
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        $this->addModifiedProperty('redirectUrl');
        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $ccType
     * @return $this
     */
    public function setCcType($ccType)
    {
        $this->ccType = $ccType;
        $this->addModifiedProperty('ccType');
        return $this;
    }

    /**
     * @return string
     */
    public function getCcType()
    {
        return $this->ccType;
    }

    /**
     * @param int $ccNumber
     * @return $this
     */
    public function setCcNumber($ccNumber)
    {
        $this->ccNumber = $ccNumber;
        $this->addModifiedProperty('ccNumber');
        return $this;
    }

    /**
     * @return int
     */
    public function getCcNumber()
    {
        return $this->ccNumber;
    }

    /**
     * @param string $ccCardholder
     * @return $this
     */
    public function setCcCardholder($ccCardholder)
    {
        $this->ccCardholder = $ccCardholder;
        $this->addModifiedProperty('ccCardholder');
        return $this;
    }

    /**
     * @return string
     */
    public function getCcCardholder()
    {
        return $this->ccCardholder;
    }

    /**
     * @param int $ccExpirationMonth
     * @return $this
     */
    public function setCcExpirationMonth($ccExpirationMonth)
    {
        $this->ccExpirationMonth = $ccExpirationMonth;
        $this->addModifiedProperty('ccExpirationMonth');
        return $this;
    }

    /**
     * @return int
     */
    public function getCcExpirationMonth()
    {
        return $this->ccExpirationMonth;
    }

    /**
     * @param int $ccExpirationYear
     * @return $this
     */
    public function setCcExpirationYear($ccExpirationYear)
    {
        $this->ccExpirationYear = $ccExpirationYear;
        $this->addModifiedProperty('ccExpirationYear');
        return $this;
    }

    /**
     * @return int
     */
    public function getCcExpirationYear()
    {
        return $this->ccExpirationYear;
    }

    /**
     * @param int $ccVerification
     * @return $this
     */
    public function setCcVerification($ccVerification)
    {
        $this->ccVerification = $ccVerification;
        $this->addModifiedProperty('ccVerification');
        return $this;
    }

    /**
     * @return int
     */
    public function getCcVerification()
    {
        return $this->ccVerification;
    }

    /**
     * @param string $debitHolder
     * @return $this
     */
    public function setDebitHolder($debitHolder)
    {
        $this->debitHolder = $debitHolder;
        $this->addModifiedProperty('debitHolder');
        return $this;
    }

    /**
     * @return string
     */
    public function getDebitHolder()
    {
        return $this->debitHolder;
    }

    /**
     * @param int $debitAccountNumber
     * @return $this
     */
    public function setDebitAccountNumber($debitAccountNumber)
    {
        $this->debitAccountNumber = $debitAccountNumber;
        $this->addModifiedProperty('debitAccountNumber');
        return $this;
    }

    /**
     * @return int
     */
    public function getDebitAccountNumber()
    {
        return $this->debitAccountNumber;
    }

    /**
     * @param int $debitBankCodeNumber
     * @return $this
     */
    public function setDebitBankCodeNumber($debitBankCodeNumber)
    {
        $this->debitBankCodeNumber = $debitBankCodeNumber;
        $this->addModifiedProperty('debitBankCodeNumber');
        return $this;
    }

    /**
     * @return int
     */
    public function getDebitBankCodeNumber()
    {
        return $this->debitBankCodeNumber;
    }

    /**
     * @param string $debitInstitute
     * @return $this
     */
    public function setDebitInstitute($debitInstitute)
    {
        $this->debitInstitute = $debitInstitute;
        $this->addModifiedProperty('debitInstitute');
        return $this;
    }

    /**
     * @return string
     */
    public function getDebitInstitute()
    {
        return $this->debitInstitute;
    }

    /**
     * @param string $pseudoCcNumber
     * @return $this
     */
    public function setPseudoCcNumber($pseudoCcNumber)
    {
        $this->pseudoCcNumber = $pseudoCcNumber;
        $this->addModifiedProperty('pseudoCcNumber');
        return $this;
    }

    /**
     * @return string
     */
    public function getPseudoCcNumber()
    {
        return $this->pseudoCcNumber;
    }

    /**
     * @param $paymentData
     * @return $this
     */
    public function setPaymentData($paymentData)
    {
        $this->paymentData = $paymentData;
        $this->paymentData_ClassName = get_class($paymentData);
        $this->addModifiedProperty('paymentData');
        return $this;
    }

    /**
     * @return null
     */
    public function getPaymentData()
    {
        if (is_array($this->paymentData) && !empty($this->paymentData_ClassName)) {
            if (is_a($this->paymentData_ClassName, '\SprykerFeature\Shared\Library\TransferObject\TransferInterface', true)) {
                $loaderName = \SprykerFeature\Shared\Library\CodeGenerator\TransferLoaderGenerator::generateGetMethodName($this->paymentData_ClassName);
                $this->paymentData = \SprykerFeature\Shared\Library\TransferLoader::$loaderName($this->paymentData);
            }
        }
        return $this->paymentData;
    }


}
