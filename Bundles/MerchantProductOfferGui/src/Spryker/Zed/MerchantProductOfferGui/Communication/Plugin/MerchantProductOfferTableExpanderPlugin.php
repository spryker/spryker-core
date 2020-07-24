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
 * @method \Spryker\Zed\MerchantProductOfferGui\MerchantProductOfferGuiConfig getConfig()
 */
class MerchantProductOfferTableExpanderPlugin extends AbstractPlugin implements ProductOfferTableExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Adds merchant name to the query select statement.
     *  - Expands query with filter by the merchant.
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
     * - Expands product offer table configuration with merchant name column.
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
     * - Expands table data with merchant name.
     *
     * @api
     *
     * @phpstan-param array<mixed> $rowData
     * @phpstan-param array<mixed> $productOfferData
     *
     * @phpstan-return array<mixed>
     *
     * @param array $rowData
     * @param array $productOfferData
     *
     * @return array
     */
    public function expandData(array $rowData, array $productOfferData): array
    {
        return $this->getFactory()
            ->createMerchantProductOfferTableExpander()
            ->expandData($rowData, $productOfferData);
    }
}
