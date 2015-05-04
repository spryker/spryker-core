<?php

namespace SprykerFeature\Shared\Payone\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Shared\Payone\Dependency\PaymentUserDataInterface;


class PaymentUserData extends AbstractTransfer implements PaymentUserDataInterface
{

    protected $creditCardHolder;

    protected $creditCardPan;

    protected $creditCardType;

    protected $creditCardExpirationMonth;

    protected $creditCardExpirationYear;

    protected $creditCardCvc2;

    protected $creditCardIssueNumber;

    protected $ecommerceMode;

    protected $creditCard3dSecureXid;

    protected $creditCard3dSecureCavv;

    protected $creditCard3dSecureEci;

    protected $creditCardPseudoCardPan;

    protected $shippingProvider;

    protected $bankCountry;

    protected $bankAccount;

    protected $bankCode;

    protected $iban;

    protected $bic;

    protected $bankGroupType;

    protected $bankAccountHolder;

    protected $financingType;

    protected $articleType = [];

    protected $articleId = [];

    protected $articlePrice = [];

    protected $articleQuantity = [];

    protected $articleName = [];

    protected $articleVat = [];


    /**
     * @param string $creditCardHolder
     * @return $this
     */
    public function setCreditCardHolder($creditCardHolder)
    {
        $this->creditCardHolder = $creditCardHolder;
    }

    /**
     * @return string
     */
    public function getCreditCardHolder()
    {
        return $this->creditCardHolder;
    }

    /**
     * @param string $creditCardPan
     * @return $this
     */
    public function setCreditCardPan($creditCardPan)
    {
        $this->creditCardPan = $creditCardPan;
    }

    /**
     * @return string
     */
    public function getCreditCardPan()
    {
        return $this->creditCardPan;
    }

    /**
     * @param string $creditCardType
     * @return $this
     */
    public function setCreditCardType($creditCardType)
    {
        $this->creditCardType = $creditCardType;
    }

    /**
     * @return string
     */
    public function getCreditCardType()
    {
        return $this->creditCardType;
    }

    /**
     * @param string $creditCardExpirationMonth
     */
    public function setCreditCardExpirationMonth($creditCardExpirationMonth)
    {
        $this->creditCardExpirationMonth = $creditCardExpirationMonth;
    }

    /**
     * @return string
     */
    public function getCreditCardExpirationMonth()
    {
        return $this->creditCardExpirationMonth;
    }

    /**
     * @param string $creditCardExpirationYear
     */
    public function setCreditCardExpirationYear($creditCardExpirationYear)
    {
        $this->creditCardExpirationYear = $creditCardExpirationYear;
    }

    /**
     * @return string
     */
    public function getCreditCardExpirationYear()
    {
        return $this->creditCardExpirationYear;
    }

    /**
     * @param string $creditCardCvc2
     */
    public function setCreditCardCvc2($creditCardCvc2)
    {
        $this->creditCardCvc2 = $creditCardCvc2;
    }

    /**
     * @return string
     */
    public function getCreditCardCvc2()
    {
        return $this->creditCardCvc2;
    }

    /**
     * @param string $creditCardIssueNumber
     */
    public function setCreditCardIssueNumber($creditCardIssueNumber)
    {
        $this->creditCardIssueNumber = $creditCardIssueNumber;
    }

    /**
     * @return string
     */
    public function getCreditCardIssueNumber()
    {
        return $this->creditCardIssueNumber;
    }

    /**
     * @param string $ecommerceMode
     */
    public function setEcommerceMode($ecommerceMode)
    {
        $this->ecommerceMode = $ecommerceMode;
    }

    /**
     * @return string
     */
    public function getEcommerceMode()
    {
        return $this->ecommerceMode;
    }

    /**
     * @param string $creditCard3dSecureXid
     */
    public function setCreditCard3dSecureXid($creditCard3dSecureXid)
    {
        $this->creditCard3dSecureXid = $creditCard3dSecureXid;
    }

    /**
     * @return string
     */
    public function getCreditCard3dSecureXid()
    {
        return $this->creditCard3dSecureXid;
    }

    /**
     * @param string $creditCard3dSecureCavv
     */
    public function setCreditCard3dSecureCavv($creditCard3dSecureCavv)
    {
        $this->creditCard3dSecureCavv = $creditCard3dSecureCavv;
    }

    /**
     * @return string
     */
    public function getCreditCard3dSecureCavv()
    {
        return $this->creditCard3dSecureCavv;
    }

    /**
     * @param string $creditCard3dSecureEci
     */
    public function setCreditCard3dSecureEci($creditCard3dSecureEci)
    {
        $this->creditCard3dSecureEci = $creditCard3dSecureEci;
    }

    /**
     * @return string
     */
    public function getCreditCard3dSecureEci()
    {
        return $this->creditCard3dSecureEci;
    }

    /**
     * @param string $creditCardPseudoCardPan
     */
    public function setCreditCardPseudoCardPan($creditCardPseudoCardPan)
    {
        $this->creditCardPseudoCardPan = $creditCardPseudoCardPan;
    }

    /**
     * @return string
     */
    public function getCreditCardPseudoCardPan()
    {
        return $this->creditCardPseudoCardPan;
    }

    /**
     * @param string $shippingProvider
     */
    public function setShippingProvider($shippingProvider)
    {
        $this->shippingProvider = $shippingProvider;
    }

    /**
     * @return string
     */
    public function getShippingProvider()
    {
        return $this->shippingProvider;
    }

    /**
     * @param string $bankCountry
     */
    public function setBankCountry($bankCountry)
    {
        $this->bankCountry = $bankCountry;
    }

    /**
     * @return string
     */
    public function getBankCountry()
    {
        return $this->bankCountry;
    }

    /**
     * @param string $bankAccount
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;
    }

    /**
     * @return string
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * @param string $bankCode
     */
    public function setBankCode($bankCode)
    {
        $this->bankCode = $bankCode;
    }

    /**
     * @return string
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }

    /**
     * @param string $iban
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param string $bic
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
    }

    /**
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * @param string $bankGroupType
     */
    public function setBankGroupType($bankGroupType)
    {
        $this->bankGroupType = $bankGroupType;
    }

    /**
     * @return string
     */
    public function getBankGroupType()
    {
        return $this->bankGroupType;
    }

    /**
     * @param string $bankAccountHolder
     */
    public function setBankAccountHolder($bankAccountHolder)
    {
        $this->bankAccountHolder = $bankAccountHolder;
    }

    /**
     * @return string
     */
    public function getBankAccountHolder()
    {
        return $this->bankAccountHolder;
    }

    /**
     * @param string $financingType
     */
    public function setFinancingType($financingType)
    {
        $this->financingType = $financingType;
    }

    /**
     * @return string
     */
    public function getFinancingType()
    {
        return $this->financingType;
    }

    /**
     * @param array $articleType
     */
    public function setArticleType(array $articleType)
    {
        $this->articleType = $articleType;
    }

    /**
     * @return array
     */
    public function getArticleType()
    {
        return $this->articleType;
    }

    /**
     * @param mixed $articleType
     */
    public function addArticleType($articleType)
    {
        $this->articleType[] = $articleType;
    }

    /**
     * @param array $articleId
     */
    public function setArticleId(array $articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * @return array
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param mixed $articleId
     */
    public function addArticleId($articleId)
    {
        $this->articleId[] = $articleId;
    }

    /**
     * @param array $articlePrice
     */
    public function setArticlePrice(array $articlePrice)
    {
        $this->articlePrice = $articlePrice;
    }

    /**
     * @return array
     */
    public function getArticlePrice()
    {
        return $this->articlePrice;
    }

    /**
     * @param mixed $articlePrice
     */
    public function addArticlePrice($articlePrice)
    {
        $this->articlePrice[] = $articlePrice;
    }

    /**
     * @param array $articleQuantity
     */
    public function setArticleQuantity(array $articleQuantity)
    {
        $this->articleQuantity = $articleQuantity;
    }

    /**
     * @return array
     */
    public function getArticleQuantity()
    {
        return $this->articleQuantity;
    }

    /**
     * @param mixed $articleQuantity
     */
    public function addArticleQuantity($articleQuantity)
    {
        $this->articleQuantity[] = $articleQuantity;
    }

    /**
     * @param array $articleName
     */
    public function setArticleName(array $articleName)
    {
        $this->articleName = $articleName;
    }

    /**
     * @return array
     */
    public function getArticleName()
    {
        return $this->articleName;
    }

    /**
     * @param mixed $articleName
     */
    public function addArticleName($articleName)
    {
        $this->articleName[] = $articleName;
    }

    /**
     * @param array $articleVat
     */
    public function setArticleVat(array $articleVat)
    {
        $this->articleVat = $articleVat;
    }

    /**
     * @return array
     */
    public function getArticleVat()
    {
        return $this->articleVat;
    }

    /**
     * @param mixed $articleVat
     * @return array
     */
    public function addArticleVat($articleVat)
    {
        $this->articleVat[] = $articleVat;
    }

}