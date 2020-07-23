<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferGui\Communication\Plugin;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferTableExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferGui\Communication\MerchantProductOfferGuiCommunicationFactory getFactory()
 */
class MerchantProductOfferTableExpanderPlugin extends AbstractPlugin implements ProductOfferTableExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands a merchant entity to query.
     * - Filters by idMerchant if it exists in request.
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
            ->createMerchantProductOfferTableExpander()
            ->expandQueryCriteria($queryCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     * - Expands a merchant name column.
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandTableConfiguration(TableConfiguration $config): TableConfiguration
    {
        return $this->getFactory()
            ->createMerchantProductOfferTableExpander()
            ->expandTableConfiguration($config);
    }

    /**
     * {@inheritDoc}
     * - Expands a merchant name to table data.
     *
     * @api
     *
     * @param array $data
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $data, array $item): array
    {
        return $this->getFactory()
            ->createMerchantProductOfferTableExpander()
            ->expandData($data, $item);
    }
}
