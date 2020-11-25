<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableSearchConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableSettingsConfigurationTransfer;

class PriceProductOfferCreateGuiTableConfigurationProvider extends AbstractPriceProductOfferGuiTableConfigurationProvider implements PriceProductOfferCreateGuiTableConfigurationProviderInterface
{
    /**
     * @phpstan-param array<mixed>|null $initialData
     *
     * @param array|null $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(?array $initialData = []): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();
        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);

        $dataSourceUrl = str_replace(static::PARAM_ID_PRODUCT_OFFER, '0', static::DATA_URL);
        $guiTableConfigurationBuilder
            ->setDataSourceUrl($dataSourceUrl)
            ->setIsItemSelectionEnabled(false)
            ->setDefaultPageSize(25);

        $guiTableConfigurationBuilder = $this->setEditableConfiguration(
            $guiTableConfigurationBuilder,
            $initialData
        );

        $guiTableSearchConfigurationTransfer = (new GuiTableSearchConfigurationTransfer())->setIsEnabled(false);
        $guiTableSettingsConfigurationTransfer = (new GuiTableSettingsConfigurationTransfer())->setEnabled(false);

        return $guiTableConfigurationBuilder->createConfiguration()
            ->setSearch($guiTableSearchConfigurationTransfer)
            ->setSettings($guiTableSettingsConfigurationTransfer);
    }
}
