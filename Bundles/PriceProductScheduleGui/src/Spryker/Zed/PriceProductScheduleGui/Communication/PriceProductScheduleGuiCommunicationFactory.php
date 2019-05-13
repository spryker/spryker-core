<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\TabCreator;
use Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\TabCreatorInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleAbstractTable;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpander;
use Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpanderInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiDependencyProvider;

class PriceProductScheduleGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator\TabCreatorInterface
     */
    public function createTabCreator(): TabCreatorInterface
    {
        return new TabCreator();
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander\AbstractProductViewExpanderInterface
     */
    public function createAbstractProductViewExpander(): AbstractProductViewExpanderInterface
    {
        return new AbstractProductViewExpander($this->getPriceProductFacade());
    }

    /**
     * @param int $idProductAbstract
     * @param int $idPriceType
     *
     * @return \Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleAbstractTable
     */
    public function createPriceProductScheduleAbstractTable(int $idProductAbstract, int $idPriceType): PriceProductScheduleAbstractTable
    {
        return new PriceProductScheduleAbstractTable($idProductAbstract, $idPriceType);
    }

    /**
     * @return \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductScheduleGuiToPriceProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductScheduleGuiDependencyProvider::FACADE_PRICE_PRODUCT);
    }
}
