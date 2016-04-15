<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container;

use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;

interface AuthorizationContainerInterface
{

    /**
     * @param string $narrative_text
     *
     * @return void
     */
    public function setNarrativeText($narrative_text);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
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
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer $personalData
     *
     * @return void
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
     *
     * @return void
     */
    public function setCurrency($currency);

    /**
     * set the system-Name
     *
     * @param string $integratorName
     *
     * @return void
     */
    public function setIntegratorName($integratorName);

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
     * @param string $solutionVersion
     *
     * @return void
     */
    public function setSolutionVersion($solutionVersion);

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
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer
     */
    public function get3dsecure();

    /**
     * @param int $portalid
     *
     * @return void
     */
    public function setPortalid($portalid);

    /**
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer $delivery
     *
     * @return void
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
     *
     * @return void
     */
    public function setParam($param);

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @param string $encoding
     *
     * @return void
     */
    public function setEncoding($encoding);

    /**
     * @param string $api_version
     *
     * @return void
     */
    public function setApiVersion($api_version);

    /**
     * @param string $clearingType
     *
     * @return void
     */
    public function setClearingType($clearingType);

    /**
     * @param string $reference
     *
     * @return void
     */
    public function setReference($reference);

    /**
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    public function getPersonalData();

    /**
     * @return string
     */
    public function getClearingType();

    /**
     * @param string $key
     *
     * @return void
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getIntegratorVersion();

    /**
     * @param PaymentMethod\AbstractPaymentMethodContainer $paymentMethod
     *
     * @return void
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
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer $secure
     *
     * @return void
     */
    public function set3dsecure(ThreeDSecureContainer $secure);

    /**
     * set the name of the solution-partner (company)
     *
     * @param string $solution_name
     *
     * @return void
     */
    public function setSolutionName($solution_name);

    /**
     * @param string $request
     *
     * @return void
     */
    public function setRequest($request);

    /**
     * @param int $aid
     *
     * @return void
     */
    public function setAid($aid);

    /**
     * @param string $mode
     *
     * @return void
     */
    public function setMode($mode);

    /**
     * @return string
     */
    public function __toString();

    /**
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer $invoicing
     *
     * @return void
     */
    public function setInvoicing(TransactionContainer $invoicing);

    /**
     * @return string
     */
    public function getApiVersion();

    /**
     * @return \Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer
     */
    public function getShippingData();

    /**
     * @return int
     */
    public function getAid();

    /**
     * @param int $amount
     *
     * @return void
     */
    public function setAmount($amount);

}
