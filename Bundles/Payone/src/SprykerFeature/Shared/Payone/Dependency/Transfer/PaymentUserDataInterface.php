<?php

namespace SprykerFeature\Shared\Payone\Dependency\Transfer;



interface PaymentUserDataInterface
{

    /**
     * @param string $creditCardHolder
     */
    public function setCreditCardHolder($creditCardHolder);

    /**
     * @return string
     */
    public function getCreditCardHolder();

    /**
     * @param string $creditCardPan
     */
    public function setCreditCardPan($creditCardPan);

    /**
     * @return string
     */
    public function getCreditCardPan();

    /**
     * @param string $creditCardType
     */
    public function setCreditCardType($creditCardType);

    /**
     * @return string
     */
    public function getCreditCardType();

    /**
     * @param string $creditCardExpirationMonth
     */
    public function setCreditCardExpirationMonth($creditCardExpirationMonth);

    /**
     * @return string
     */
    public function getCreditCardExpirationMonth();

    /**
     * @param string $creditCardExpirationYear
     */
    public function setCreditCardExpirationYear($creditCardExpirationYear);

    /**
     * @return string
     */
    public function getCreditCardExpirationYear();

    /**
     * @param string $creditCardCvc2
     */
    public function setCreditCardCvc2($creditCardCvc2);

    /**
     * @return string
     */
    public function getCreditCardCvc2();

    /**
     * @param string $creditCardIssueNumber
     */
    public function setCreditCardIssueNumber($creditCardIssueNumber);

    /**
     * @return string
     */
    public function getCreditCardIssueNumber();

    /**
     * @param string $ecommerceMode
     */
    public function setEcommerceMode($ecommerceMode);

    /**
     * @return string
     */
    public function getEcommerceMode();

    /**
     * @param string $creditCard3dSecureXid
     */
    public function setCreditCard3dSecureXid($creditCard3dSecureXid);

    /**
     * @return string
     */
    public function getCreditCard3dSecureXid();

    /**
     * @param string $creditCard3dSecureCavv
     */
    public function setCreditCard3dSecureCavv($creditCard3dSecureCavv);

    /**
     * @return string
     */
    public function getCreditCard3dSecureCavv();

    /**
     * @param string $creditCard3dSecureEci
     */
    public function setCreditCard3dSecureEci($creditCard3dSecureEci);

    /**
     * @return string
     */
    public function getCreditCard3dSecureEci();

    /**
     * @param string $creditCardPseudoCardPan
     */
    public function setCreditCardPseudoCardPan($creditCardPseudoCardPan);

    /**
     * @return string
     */
    public function getCreditCardPseudoCardPan();

    /**
     * @param string $shippingProvider
     */
    public function setShippingProvider($shippingProvider);

    /**
     * @return string
     */
    public function getShippingProvider();

    /**
     * @param string $bankCountry
     */
    public function setBankCountry($bankCountry);

    /**
     * @return string
     */
    public function getBankCountry();

    /**
     * @param string $bankAccount
     */
    public function setBankAccount($bankAccount);

    /**
     * @return string
     */
    public function getBankAccount();

    /**
     * @param string $bankCode
     */
    public function setBankCode($bankCode);

    /**
     * @return string
     */
    public function getBankCode();

    /**
     * @param string $iban
     */
    public function setIban($iban);

    /**
     * @return string
     */
    public function getIban();

    /**
     * @param string $bic
     */
    public function setBic($bic);

    /**
     * @return string
     */
    public function getBic();

    /**
     * @param string $bankGroupType
     */
    public function setBankGroupType($bankGroupType);

    /**
     * @return string
     */
    public function getBankGroupType();

    /**
     * @param string $bankAccountHolder
     */
    public function setBankAccountHolder($bankAccountHolder);

    /**
     * @return string
     */
    public function getBankAccountHolder();

    /**
     * @param string $financingType
     */
    public function setFinancingType($financingType);

    /**
     * @return string
     */
    public function getFinancingType();

    /**
     * @param array $articleType
     */
    public function setArticleType(array $articleType);

    /**
     * @return array
     */
    public function getArticleType();

    /**
     * @param mixed $articleType
     */
    public function addArticleType($articleType);

    /**
     * @param array $articleId
     */
    public function setArticleId(array $articleId);

    /**
     * @return array
     */
    public function getArticleId();

    /**
     * @param mixed $articleId
     */
    public function addArticleId($articleId);

    /**
     * @param array $articlePrice
     */
    public function setArticlePrice(array $articlePrice);

    /**
     * @return array
     */
    public function getArticlePrice();

    /**
     * @param mixed $articlePrice
     */
    public function addArticlePrice($articlePrice);

    /**
     * @param array $articleQuantity
     */
    public function setArticleQuantity(array $articleQuantity);

    /**
     * @return array
     */
    public function getArticleQuantity();

    /**
     * @param mixed $articleQuantity
     */
    public function addArticleQuantity($articleQuantity);

    /**
     * @param array $articleName
     */
    public function setArticleName(array $articleName);

    /**
     * @return array
     */
    public function getArticleName();

    /**
     * @param mixed $articleName
     */
    public function addArticleName($articleName);

    /**
     * @param array $articleVat
     */
    public function setArticleVat(array $articleVat);

    /**
     * @return array
     */
    public function getArticleVat();

    /**
     * @param mixed $articleVat
     */
    public function addArticleVat($articleVat);

}