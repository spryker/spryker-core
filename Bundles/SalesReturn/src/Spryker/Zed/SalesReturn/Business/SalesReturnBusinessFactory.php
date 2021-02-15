<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculator;
use Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculatorInterface;
use Spryker\Zed\SalesReturn\Business\Expander\OrderRemunerationTotalExpander;
use Spryker\Zed\SalesReturn\Business\Expander\OrderRemunerationTotalExpanderInterface;
use Spryker\Zed\SalesReturn\Business\Generator\ReturnReferenceGenerator;
use Spryker\Zed\SalesReturn\Business\Generator\ReturnReferenceGeneratorInterface;
use Spryker\Zed\SalesReturn\Business\Reader\ReturnReader;
use Spryker\Zed\SalesReturn\Business\Reader\ReturnReaderInterface;
use Spryker\Zed\SalesReturn\Business\Setter\IsReturnableSetter;
use Spryker\Zed\SalesReturn\Business\Setter\IsReturnableSetterInterface;
use Spryker\Zed\SalesReturn\Business\Setter\ItemRemunerationAmountSetter;
use Spryker\Zed\SalesReturn\Business\Setter\ItemRemunerationAmountSetterInterface;
use Spryker\Zed\SalesReturn\Business\Triggerer\OmsEventTriggerer;
use Spryker\Zed\SalesReturn\Business\Triggerer\OmsEventTriggererInterface;
use Spryker\Zed\SalesReturn\Business\Validator\ReturnValidator;
use Spryker\Zed\SalesReturn\Business\Validator\ReturnValidatorInterface;
use Spryker\Zed\SalesReturn\Business\Writer\ReturnWriter;
use Spryker\Zed\SalesReturn\Business\Writer\ReturnWriterInterface;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToOmsFacadeInterface;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface;
use Spryker\Zed\SalesReturn\Dependency\Service\SalesReturnToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReturn\SalesReturnDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesReturn\SalesReturnConfig getConfig()
 */
class SalesReturnBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesReturn\Business\Setter\ItemRemunerationAmountSetterInterface
     */
    public function createItemRemunerationAmountSetter(): ItemRemunerationAmountSetterInterface
    {
        return new ItemRemunerationAmountSetter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Writer\ReturnWriterInterface
     */
    public function createReturnWriter(): ReturnWriterInterface
    {
        return new ReturnWriter(
            $this->getEntityManager(),
            $this->createReturnValidator(),
            $this->createReturnReader(),
            $this->createReturnReferenceGenerator(),
            $this->createOmsEventTriggerer(),
            $this->getReturnPreCreatePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Validator\ReturnValidatorInterface
     */
    public function createReturnValidator(): ReturnValidatorInterface
    {
        return new ReturnValidator(
            $this->getStoreFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Reader\ReturnReaderInterface
     */
    public function createReturnReader(): ReturnReaderInterface
    {
        return new ReturnReader(
            $this->getRepository(),
            $this->getSalesFacade(),
            $this->createReturnTotalCalculator()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Triggerer\OmsEventTriggererInterface
     */
    public function createOmsEventTriggerer(): OmsEventTriggererInterface
    {
        return new OmsEventTriggerer(
            $this->getOmsFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculatorInterface
     */
    public function createReturnTotalCalculator(): ReturnTotalCalculatorInterface
    {
        return new ReturnTotalCalculator();
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Expander\OrderRemunerationTotalExpanderInterface
     */
    public function createOrderRemunerationTotalExpander(): OrderRemunerationTotalExpanderInterface
    {
        return new OrderRemunerationTotalExpander();
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Generator\ReturnReferenceGeneratorInterface
     */
    public function createReturnReferenceGenerator(): ReturnReferenceGeneratorInterface
    {
        return new ReturnReferenceGenerator(
            $this->getRepository(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Setter\IsReturnableSetterInterface
     */
    public function createIsReturnableSetter(): IsReturnableSetterInterface
    {
        return new IsReturnableSetter(
            $this->getConfig(),
            $this->getUtilDateTimeService()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesReturnToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface
     */
    public function getStoreFacade(): SalesReturnToStoreFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToOmsFacadeInterface
     */
    public function getOmsFacade(): SalesReturnToOmsFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Dependency\Service\SalesReturnToUtilDateTimeServiceInterface
     */
    protected function getUtilDateTimeService(): SalesReturnToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SalesReturnDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnPreCreatePluginInterface[]
     */
    protected function getReturnPreCreatePlugins()
    {
        return $this->getProvidedDependency(SalesReturnDependencyProvider::PLUGINS_RETURN_PRE_CREATE);
    }
}
