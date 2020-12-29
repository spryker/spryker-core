<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch;

use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantSearchExtension\Dependency\Plugin\MerchantSearchDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantCategorySearch\Communication\MerchantCategorySearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantCategorySearch\MerchantCategorySearchConfig getConfig()
 */
class MerchantCategoryMerchantSearchDataExpanderPlugin extends AbstractPlugin implements MerchantSearchDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands merchant search data with merchant category keys.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    public function expand(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): MerchantSearchCollectionTransfer
    {
        return $this->getFactory()
            ->createMerchantCategorySearchExpander()
            ->expand($merchantSearchCollectionTransfer);
    }
}
