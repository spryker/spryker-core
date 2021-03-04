<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface ProductConcreteAttributeGuiTableConfigurationProviderInterface
{
    /**
     * @phpstan-param ArrayObject<string, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     *
     * @param string[] $attributes
     * @param string[] $superAttributes
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransfers
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(array $attributes, array $superAttributes, ArrayObject $localizedAttributeTransfers): GuiTableConfigurationTransfer;
}
