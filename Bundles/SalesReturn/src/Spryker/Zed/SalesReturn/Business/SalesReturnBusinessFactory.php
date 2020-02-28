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
use Spryker\Zed\SalesReturn\Business\Expander\ReturnExpander;
use Spryker\Zed\SalesReturn\Business\Expander\ReturnExpanderInterface;
use Spryker\Zed\SalesReturn\Business\Generator\ReturnReferenceGenerator;
use Spryker\Zed\SalesReturn\Business\Generator\ReturnReferenceGeneratorInterface;
use Spryker\Zed\SalesReturn\Business\Reader\ReturnReasonReader;
use Spryker\Zed\SalesReturn\Business\Reader\ReturnReasonReaderInterface;
use Spryker\Zed\SalesReturn\Business\Setter\ItemRemunerationAmountSetter;
use Spryker\Zed\SalesReturn\Business\Setter\ItemRemunerationAmountSetterInterface;
use Spryker\Zed\SalesReturn\Business\Validator\ReturnValidator;
use Spryker\Zed\SalesReturn\Business\Validator\ReturnValidatorInterface;
use Spryker\Zed\SalesReturn\Business\Writer\ReturnWriter;
use Spryker\Zed\SalesReturn\Business\Writer\ReturnWriterInterface;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface;
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
            $this->createReturnExpander(),
            $this->createReturnReferenceGenerator(),
            $this->getSalesFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Validator\ReturnValidatorInterface
     */
    public function createReturnValidator(): ReturnValidatorInterface
    {
        return new ReturnValidator($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\SalesReturn\Business\Expander\ReturnExpanderInterface
     */
    public function createReturnExpander(): ReturnExpanderInterface
    {
        return new ReturnExpander(
            $this->createReturnTotalCalculator()
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
     * @return \Spryker\Zed\SalesReturn\Business\Reader\ReturnReasonReaderInterface
     */
    public function createReturnReasonReader(): ReturnReasonReaderInterface
    {
        return new ReturnReasonReader($this->getRepository());
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
}
