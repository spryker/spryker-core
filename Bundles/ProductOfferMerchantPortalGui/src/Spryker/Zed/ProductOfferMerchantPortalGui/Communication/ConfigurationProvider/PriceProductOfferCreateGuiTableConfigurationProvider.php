<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

class PriceProductOfferCreateGuiTableConfigurationProvider extends AbstractPriceProductOfferGuiTableConfigurationProvider implements PriceProductOfferCreateGuiTableConfigurationProviderInterface
{
    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\CreateProductOfferController::priceTableDataAction()
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/create-product-offer/price-table-data';

    /**
     * @phpstan-param array<mixed> $initialData
     *
     * @param array $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(array $initialData = []): GuiTableConfigurationTransfer
    {
        $priceTypeTransfers = $this->priceProductFacade->getPriceTypeValues();
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();
        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder, $priceTypeTransfers);

        $guiTableConfigurationBuilder
            ->setDataSourceUrl(static::DATA_URL)
            ->setIsItemSelectionEnabled(false)
            ->setDefaultPageSize(25)
            ->isSearchEnabled(false)
            ->isColumnConfiguratorEnabled(false);

        $guiTableConfigurationBuilder = $this->setEditableConfiguration(
            $guiTableConfigurationBuilder,
            $priceTypeTransfers,
            $initialData
        );

        return $guiTableConfigurationBuilder->createConfiguration();
    }
}
