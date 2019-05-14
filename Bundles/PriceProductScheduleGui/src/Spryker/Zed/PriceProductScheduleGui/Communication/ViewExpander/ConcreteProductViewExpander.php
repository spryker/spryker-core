<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleConcreteTable;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface;

class ConcreteProductViewExpander implements ConcreteProductViewExpanderInterface
{
    protected const PRICE_TYPE_TEMPLATE = '@PriceProductScheduleGui/_partials/price-type-tabs/price-type-tab.twig';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(PriceProductScheduleGuiToPriceProductFacadeInterface $priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param array $viewData
     *
     * @return array
     */
    public function expandProductConcreteEditViewData(array $viewData): array
    {
        $priceTypeTransfers = $this->priceProductFacade
            ->getPriceTypeValues();
        $priceTypeTabs = new TabsViewTransfer();
        $tablesByType = [];

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            $priceTypeTab = new TabItemTransfer();
            $priceTypeTab->setName($priceTypeTransfer->getName())
                ->setTitle($priceTypeTransfer->getName())
                ->setTemplate(static::PRICE_TYPE_TEMPLATE);
            $priceTypeTabs->addTab($priceTypeTab);

            $tablesByType[$priceTypeTransfer->getName()] = (
                new PriceProductScheduleConcreteTable(
                    $viewData['idProduct'],
                    $priceTypeTransfer->getIdPriceType()
                )
            )->render();
        }

        $priceTypeTabs->setActiveTabName($priceTypeTabs->getTabs()[0]->getName());

        $viewData['priceTypeTabs'] = $priceTypeTabs;
        $viewData['tablesByType'] = $tablesByType;

        return $viewData;
    }
}
