<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Payone\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\PayoneCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Payone\PayoneConfig;
use SprykerFeature\Zed\Payone\PayoneDependencyProvider;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainerInterface;
use SprykerFeature\Zed\Refund\Business\RefundFacade;

/**
 * @method PayoneConfig getConfig()
 * @method PayoneCommunication getFactory()
 * @method PayoneQueryContainerInterface getQueryContainer()
 */
class PayoneDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return OmsFacade
     */
    public function createOmsFacade()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::FACADE_OMS);
    }

    /**
     * @return RefundFacade
     */
    public function createRefundFacade()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::FACADE_REFUND);
    }

}
