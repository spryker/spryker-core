<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface ProductAttributeGuiTableConfigurationProviderInterface
{
    /**
     * @phpstan-param ArrayObject<string, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     *
     * @param string[] $attributes
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransfers
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(array $attributes, ArrayObject $localizedAttributeTransfers): GuiTableConfigurationTransfer;
}
