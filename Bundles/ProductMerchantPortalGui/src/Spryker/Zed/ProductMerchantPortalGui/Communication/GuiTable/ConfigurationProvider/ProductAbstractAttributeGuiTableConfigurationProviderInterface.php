<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface ProductAbstractAttributeGuiTableConfigurationProviderInterface
{
    /**
     * @param int $idProductAbstract
     * @param array<string, array<string, mixed>> $attributesInitialData
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(
        int $idProductAbstract,
        array $attributesInitialData
    ): GuiTableConfigurationTransfer;
}
