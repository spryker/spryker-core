<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Plugin\AvailabilityGui;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityAbstractTableQueryCriteriaExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductGui\Communication\MerchantProductGuiCommunicationFactory getFactory()
 */
class MerchantProductAvailabilityAbstractTableQueryCriteriaExpanderPlugin extends AbstractPlugin implements AvailabilityAbstractTableQueryCriteriaExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands QueryCriteriaTransfer with QueryJoinTransfer for filtering by idMerchant.
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
