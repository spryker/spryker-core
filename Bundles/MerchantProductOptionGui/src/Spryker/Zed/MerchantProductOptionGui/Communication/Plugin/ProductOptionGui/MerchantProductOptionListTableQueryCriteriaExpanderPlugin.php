<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionGui\Communication\Plugin\ProductOptionGui;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOptionGuiExtension\Dependency\Plugin\ProductOptionListTableQueryCriteriaExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOptionGui\Communication\MerchantProductOptionGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOptionGui\MerchantProductOptionGuiConfig getConfig()
 */
class MerchantProductOptionListTableQueryCriteriaExpanderPlugin extends AbstractPlugin implements ProductOptionListTableQueryCriteriaExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `QueryCriteriaTransfer` with merchant product option group criteria for expanding default query running in `ProductOptionListTable`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteria(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer
    {
        return $this->getFactory()
            ->createMerchantProductQueryCriteriaExpander()
            ->expandQueryCriteria($queryCriteriaTransfer);
    }
}
