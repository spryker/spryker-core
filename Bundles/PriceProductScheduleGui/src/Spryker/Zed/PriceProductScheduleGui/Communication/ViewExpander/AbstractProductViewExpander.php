<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\ViewExpander;

use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Table\PriceProductScheduleAbstractTable;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface;

class AbstractProductViewExpander implements AbstractProductViewExpanderInterface
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
    public function expandAbstractProductEditViewData(array $viewData): array
    {
        $priceTypeTransfers = $this->priceProductFacade
            ->getPriceTypeValues();
        $priceTypeTabs = new TabsViewTransfer();
        $tablesByType = [];

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            $priceTypeTab = $this->createPriceTypeTab($priceTypeTransfer);
            $priceTypeTabs->addTab($priceTypeTab);
            $tablesByType[$priceTypeTransfer->getName()] = $this->createTableByType(
                $viewData,
                $priceTypeTransfer
            );
        }

        $priceTypeTabs->setActiveTabName($priceTypeTabs->getTabs()[0]->getName());

        $viewData['priceTypeTabs'] = $priceTypeTabs;
        $viewData['tablesByType'] = $tablesByType;

        return $viewData;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\TabItemTransfer
     */
    protected function createPriceTypeTab(PriceTypeTransfer $priceTypeTransfer): TabItemTransfer
    {
        $tabItemTransfer = new TabItemTransfer();

        return $tabItemTransfer->setName($priceTypeTransfer->getName())
            ->setTitle($priceTypeTransfer->getName())
            ->setTemplate(static::PRICE_TYPE_TEMPLATE);
    }

    /**
     * @param array $viewData
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     *
     * @return string
     */
    protected function createTableByType(array $viewData, PriceTypeTransfer $priceTypeTransfer): string
    {
        return (
            new PriceProductScheduleAbstractTable(
                $viewData['idProductAbstract'],
                $priceTypeTransfer->getIdPriceType()
            )
        )->render();
    }
}
