<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferGui\Communication\Expander;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface MerchantProductOfferTableExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteria(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer;

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandTableConfiguration(TableConfiguration $config): TableConfiguration;

    /**
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
    public function expandData(array $rowData, array $productOfferData): array;
}
