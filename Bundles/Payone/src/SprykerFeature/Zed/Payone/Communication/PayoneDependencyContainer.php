<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Payone\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\PayoneCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Oms\Business\OmsFacade;
use SprykerFeature\Zed\Payone\PayoneDependencyProvider;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainerInterface;

/**
 * @method PayoneCommunication getFactory()
 * @method PayoneQueryContainerInterface getQueryContainer()
 */
class PayoneDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return OmsFacade
     */
    public function getOmsFacade()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::FACADE_OMS);
    }

}
