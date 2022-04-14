<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Payment\Communication\Mapper\OrderMapper;
use Spryker\Zed\Payment\Communication\Mapper\OrderMapperInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeBridge;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreReferenceFacadeInterface;
use Spryker\Zed\Payment\PaymentDependencyProvider;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 * @method \Spryker\Zed\Payment\Business\PaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface getRepository()
 * @method \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface getEntityManager()
 */
class PaymentCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeBridge
     */
    public function getStoreFacade(): PaymentToStoreFacadeBridge
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreReferenceFacadeInterface
     */
    public function getStoreReferenceFacade(): PaymentToStoreReferenceFacadeInterface
    {
        return $this->getProvidedDependency(PaymentDependencyProvider::FACADE_STORE_REFERENCE);
    }

    /**
     * @return \Spryker\Zed\Payment\Communication\Mapper\OrderMapperInterface
     */
    public function createOrderMapper(): OrderMapperInterface
    {
        return new OrderMapper();
    }
}
