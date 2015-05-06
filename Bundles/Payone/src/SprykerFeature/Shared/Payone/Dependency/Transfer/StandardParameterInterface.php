<?php

namespace SprykerFeature\Shared\Payone\Dependency\Transfer;


interface StandardParameterInterface
{

    /**
     * @return string
     */
    public function getEncoding();

    /**
     * @return string
     */
    public function getMid();

    /**
     * @return string
     */
    public function getAid();

    /**
     * @return string
     */
    public function getPortalId();

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getPaymentGatewayUrl();

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * @return string
     */
    public function getLanguage();

    /**
     * @return string
     */
    public function getRedirectSuccessUrl();

    /**
     * @return string
     */
    public function getRedirectBackUrl();

    /**
     * @return string
     */
    public function getRedirectErrorUrl();

}