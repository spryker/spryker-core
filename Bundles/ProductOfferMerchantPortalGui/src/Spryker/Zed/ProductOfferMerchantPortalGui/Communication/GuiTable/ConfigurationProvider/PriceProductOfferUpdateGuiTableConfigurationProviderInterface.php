<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface PriceProductOfferUpdateGuiTableConfigurationProviderInterface
{
    /**
     * @phpstan-param array<mixed> $initialData
     *
     * @param int $idProductOffer
     * @param array $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(int $idProductOffer, array $initialData = []): GuiTableConfigurationTransfer;
}
