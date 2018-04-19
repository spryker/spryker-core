<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Offer\Business\Model\Hydrator\OfferQuoteExpander;
use Spryker\Zed\Offer\Business\Model\Hydrator\OfferQuoteExpanderInterface;
use Spryker\Zed\Offer\Business\Model\Hydrator\OfferSavingAmountHydrator;
use Spryker\Zed\Offer\Business\Model\Hydrator\OfferSavingAmountHydratorInterface;
use Spryker\Zed\Offer\Business\Model\OfferGrandTotalCalculator;
use Spryker\Zed\Offer\Business\Model\OfferGrandTotalCalculatorInterface;
use Spryker\Zed\Offer\Business\Model\OfferItemSubtotalAggregator;
use Spryker\Zed\Offer\Business\Model\OfferItemSubtotalAggregatorInterface;
use Spryker\Zed\Offer\Business\Model\OfferPluginExecutor;
use Spryker\Zed\Offer\Business\Model\OfferReader;
use Spryker\Zed\Offer\Business\Model\OfferReaderInterface;
use Spryker\Zed\Offer\Business\Model\OfferWriter;
use Spryker\Zed\Offer\Business\Model\OfferWriterInterface;
use Spryker\Zed\Offer\Dependency\Facade\OfferToCustomerFacadeInterface;
use Spryker\Zed\Offer\Dependency\Facade\OfferToMessengerFacadeInterface;
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
            $this->getConfig(),
            $this->createOfferPluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\Offer\Business\Model\OfferPluginExecutorInterface
     */
    public function createOfferPluginExecutor()
    {
        return new OfferPluginExecutor(
            $this->getOfferHydratorPlugins(),
            $this->getOfferDoUpdatePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Offer\Business\Model\Hydrator\OfferQuoteExpanderInterface
     */
    public function createOfferQuoteExpander(): OfferQuoteExpanderInterface
    {
        return new OfferQuoteExpander(
            $this->getCustomerFacade(),
            $this->createOfferReader()
        );
    }

    /**
     * @return \Spryker\Zed\Offer\Business\Model\Hydrator\OfferSavingAmountHydratorInterface
     */
    public function createOfferSavingAmountHydrator(): OfferSavingAmountHydratorInterface
    {
        return new OfferSavingAmountHydrator(
            $this->getMessengerFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Offer\Business\Model\OfferItemSubtotalAggregatorInterface
     */
    public function createOfferItemSubtotalAggregator(): OfferItemSubtotalAggregatorInterface
    {
        return new OfferItemSubtotalAggregator();
    }

    /**
     * @return \Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface[]
     */
    public function getOfferHydratorPlugins(): array
    {
        return $this->getProvidedDependency(OfferDependencyProvider::PLUGINS_OFFER_HYDRATOR);
    }

    /**
     * @return \Spryker\Zed\Offer\Dependency\Facade\OfferToCustomerFacadeInterface
     */
    public function getCustomerFacade(): OfferToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(OfferDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\Offer\Dependency\Plugin\OfferDoUpdatePluginInterface[]
     */
    public function getOfferDoUpdatePlugins(): array
    {
        return $this->getProvidedDependency(OfferDependencyProvider::PLUGINS_OFFER_DO_UPDATE);
    }

    /**
     * @return \Spryker\Zed\Offer\Dependency\Facade\OfferToMessengerFacadeInterface
     */
    public function getMessengerFacade(): OfferToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(OfferDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\Offer\Business\Model\OfferGrandTotalCalculatorInterface
     */
    public function createOfferGrandTotalCalculator(): OfferGrandTotalCalculatorInterface
    {
        return new OfferGrandTotalCalculator();
    }
}
