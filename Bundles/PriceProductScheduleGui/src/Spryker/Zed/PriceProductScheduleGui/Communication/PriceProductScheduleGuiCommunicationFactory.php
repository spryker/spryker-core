<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatter;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\CurrencyMapper;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\CurrencyMapperInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\AbstractProductTabCreator;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\AbstractProductTabCreatorInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\ConcreteProductTabCreator;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\ConcreteProductTabCreatorInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleAbstractTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleConcreteTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpander;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpanderInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ConcreteProductViewExpander;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ConcreteProductViewExpanderInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ViewExpanderTableFactoryInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToTranslatorFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiDependencyProvider;

class PriceProductScheduleGuiCommunicationFactory extends AbstractCommunicationFactory implements ViewExpanderTableFactoryInterface
{
    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\AbstractProductTabCreatorInterface
     */
    public function createAbstractProductTabCreator(): AbstractProductTabCreatorInterface
    {
        return new AbstractProductTabCreator();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\ConcreteProductTabCreatorInterface
     */
    public function createConcreteProductTabCreator(): ConcreteProductTabCreatorInterface
    {
        return new ConcreteProductTabCreator();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\RowFormatterInterface
     */
    public function createRowFormatter(): RowFormatterInterface
    {
        return new RowFormatter($this->getMoneyFacade(), $this->getStoreFacade(), $this->createCurrencyMapper());
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\CurrencyMapperInterface
     */
    public function createCurrencyMapper(): CurrencyMapperInterface
    {
        return new CurrencyMapper();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpanderInterface
     */
    public function createAbstractProductViewExpander(): AbstractProductViewExpanderInterface
    {
        return new AbstractProductViewExpander(
            $this->getPriceProductFacade(),
            $this->getTranslatorFacade(),
            $this
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\ConcreteProductViewExpanderInterface
     */
    public function createConcreteProductViewExpander(): ConcreteProductViewExpanderInterface
    {
        return new ConcreteProductViewExpander(
            $this->getPriceProductFacade(),
            $this->getTranslatorFacade(),
            $this
        );
    }

    /**
     * @param int $idProductAbstract
     * @param int $idPriceType
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleAbstractTable
     */
    public function createPriceProductScheduleAbstractTable(int $idProductAbstract, int $idPriceType): PriceProductScheduleAbstractTable
    {
        return new PriceProductScheduleAbstractTable(
            $idProductAbstract,
            $idPriceType,
            $this->createRowFormatter()
        );
    }

    /**
     * @param int $idProductConcrete
     * @param int $idPriceType
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleConcreteTable
     */
    public function createPriceProductScheduleConcreteTable(int $idProductConcrete, int $idPriceType): PriceProductScheduleConcreteTable
    {
        return new PriceProductScheduleConcreteTable(
            $idProductConcrete,
            $idPriceType,
            $this->createRowFormatter()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductScheduleGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): PriceProductScheduleGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): PriceProductScheduleGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): PriceProductScheduleGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_MONEY);
    }
}
