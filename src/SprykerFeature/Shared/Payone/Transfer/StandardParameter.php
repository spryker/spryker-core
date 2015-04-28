<?php

namespace SprykerFeature\Shared\Payone\Transfer;


use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class StandardParameter extends AbstractTransfer implements StandardParameterInterface
{

    /**
     * @var string
     */
    protected $encoding;
    /**
     * @return string
     */
    protected $mid;
    /**
     * @return string
     */
    protected $aid;
    /**
     * @return string
     */
    protected $portalId;
    /**
     * @return string
     */
    protected $key;
    /**
     * @return string
     */
    protected $paymentGatewayUrl;
    /**
     * @var string
     */
    protected $currency;
    /**
     * @var string
     */
    protected $language;
    /**
     * @var string
     */
    protected $redirectSuccessUrl;
    /**
     * @var string
     */
    protected $redirectBackUrl;
    /**
     * @var string
     */
    protected $redirectErrorUrl;


    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @param string $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * @param string $mid
     */
    public function setMid($mid)
    {
        $this->mid = $mid;
    }

    /**
     * @return string
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * @param string $aid
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
    }

    /**
     * @return string
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * @param $portalId
     */
    public function setPortalId($portalId)
    {
        $this->portalId = $portalId;
    }

    /**
     * @return string
     */
    public function getPortalId()
    {
        return $this->portalId;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $paymentGatewayUrl
     */
    public function setPaymentGatewayUrl($paymentGatewayUrl)
    {
        $this->paymentGatewayUrl = $paymentGatewayUrl;
    }

    /**
     * @return string
     */
    public function getPaymentGatewayUrl()
    {
        return $this->paymentGatewayUrl;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $redirectSuccessUrl
     */
    public function setRedirectSuccessUrl($redirectSuccessUrl)
    {
        $this->redirectSuccessUrl = $redirectSuccessUrl;
    }

    /**
     * @return string
     */
    public function getRedirectSuccessUrl()
    {
        return $this->redirectSuccessUrl;
    }

    /**
     * @param string $redirectBackUrl
     */
    public function setRedirectBackUrl($redirectBackUrl)
    {
        $this->redirectBackUrl = $redirectBackUrl;
    }

    /**
     * @return string
     */
    public function getRedirectBackUrl()
    {
        return $this->redirectBackUrl;
    }

    /**
     * @param string $redirectErrorUrl
     */
    public function setRedirectErrorUrl($redirectErrorUrl)
    {
        $this->redirectErrorUrl = $redirectErrorUrl;
    }

    /**
     * @return string
     */
    public function getRedirectErrorUrl()
    {
        return $this->redirectErrorUrl;
    }

}