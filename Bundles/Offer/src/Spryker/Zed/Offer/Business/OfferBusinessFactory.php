<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Offer\Business\Model\OfferPluginExecutor;
use Spryker\Zed\Offer\Business\Model\OfferReader;
use Spryker\Zed\Offer\Business\Model\OfferReaderInterface;
use Spryker\Zed\Offer\Business\Model\OfferWriter;
use Spryker\Zed\Offer\Business\Model\OfferWriterInterface;
use Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface;
use Spryker\Zed\Offer\OfferDependencyProvider;

/**
 * @method \Spryker\Zed\Offer\OfferConfig getConfig()
 * @method \Spryker\Zed\Offer\Persistence\OfferRepositoryInterface getRepository()
 * @method \Spryker\Zed\Offer\Persistence\OfferEntityManagerInterface getEntityManager()
 */
class OfferBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Offer\Business\Model\OfferReaderInterface
     */
    public function createOfferReader(): OfferReaderInterface
    {
        return new OfferReader(
            $this->getRepository(),
            $this->createOfferPluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface
     */
    public function getSalesFacade(): OfferToSalesFacadeInterface
    {
        return $this->getProvidedDependency(OfferDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\Offer\Business\Model\OfferWriterInterface
     */
    public function createOfferWriter(): OfferWriterInterface
    {
        return new OfferWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Offer\Business\Model\OfferPluginExecutorInterface
     */
    public function createOfferPluginExecutor()
    {
        return new OfferPluginExecutor(
            $this->getOfferHydratorPlugins()
        );
    }

    /**
     * @return array
     */
    public function getOfferHydratorPlugins(): array
    {
        return $this->getProvidedDependency(OfferDependencyProvider::PLUGINS_OFFER_HYDRATOR);
    }
}
