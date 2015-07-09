<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;

interface AuthorizationContainerInterface
{

    /**
     * @param string $narrative_text
     */
    public function setNarrativeText($narrative_text);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    public function getInvoicing();

    /**
     * @return string
     */
    public function getNarrativeText();

    /**
     * @return int
     */
    public function getPortalid();

    /**
     * @param PersonalContainer $personalData
     */
    public function setPersonalData(PersonalContainer $personalData);

    /**
     * @return string
     */
    public function getParam();

    /**
     * @return string
     */
    public function getRequest();

    /**
     * @param string $currency
     */
    public function setCurrency($currency);

    /**
     * set the system-Name
     *
     * @param string $integrator_name
     */
    public function setIntegratorName($integrator_name);

    /**
     * @return string
     */
    public function getIntegratorName();

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * set the version of the solution-partner's app / extension / plugin / etc..
     *
     * @param string $solution_version
     */
    public function setSolutionVersion($solution_version);

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string
     */
    public function getReference();

    /**
     * @return string
     */
    public function getSolutionVersion();

    /**
     * @return ThreeDSecureContainer
     */
    public function get3dsecure();

    /**
     * @param int $portalid
     */
    public function setPortalid($portalid);

    /**
     * @param ShippingContainer $delivery
     */
    public function setShippingData(ShippingContainer $delivery);

    /**
     * @return string
     */
    public function getEncoding();

    /**
     * @return string
     */
    public function getSolutionName();

    /**
     * @param string $param
     */
    public function setParam($param);

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @param string $encoding
     */
    public function setEncoding($encoding);

    /**
     * @param string $api_version
     */
    public function setApiVersion($api_version);

    /**
     * @param string $clearingtype
     */
    public function setClearingType($clearingType);

    /**
     * @param string $reference
     */
    public function setReference($reference);

    /**
     * @return PersonalContainer
     */
    public function getPersonalData();

    /**
     * @return string
     */
    public function getClearingType();

    /**
     * @param string $key
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getIntegratorVersion();

    /**
     * @param PaymentMethod\AbstractPaymentMethodContainer $paymentMethod
     */
    public function setPaymentMethod(AbstractPaymentMethodContainer $paymentMethod);

    /**
     * @return PaymentMethod\AbstractPaymentMethodContainer
     */
    public function getPaymentMethod();

    /**
     * @return int
     */
    public function getMid();

    /**
     * @return string
     */
    public function getMode();

    /**
     * @param ThreeDSecureContainer $secure
     */
    public function set3dsecure(ThreeDSecureContainer $secure);

    /**
     * set the name of the solution-partner (company)
     *
     * @param string $solution_name
     */
    public function setSolutionName($solution_name);

    /**
     * @param string $request
     */
    public function setRequest($request);

    /**
     * @param int $aid
     */
    public function setAid($aid);

    /**
     * @param string $mode
     */
    public function setMode($mode);

    /**
     * @return string
     */
    public function __toString();

    /**
     * @param \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer $invoicing
     */
    public function setInvoicing(TransactionContainer $invoicing);

    /**
     * @return string
     */
    public function getApiVersion();

    /**
     * @return ShippingContainer
     */
    public function getShippingData();

    /**
     * @return int
     */
    public function getAid();

    /**
     * @param int $amount
     */
    public function setAmount($amount);

}
