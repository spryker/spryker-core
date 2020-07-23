<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

/**
 * Use this plugin if wants to expand table data.
 */
interface ProductOfferTableExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands product offer table query criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteria(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer;

    /**
     * Specification:
     * - Expands product offer table configuration.
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandTableConfiguration(TableConfiguration $config): TableConfiguration;

    /**
     * Specification:
     * - Expands product offer table view data.
     *
     * @api
     *
     * @param array $data
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $data, array $item): array;
}
