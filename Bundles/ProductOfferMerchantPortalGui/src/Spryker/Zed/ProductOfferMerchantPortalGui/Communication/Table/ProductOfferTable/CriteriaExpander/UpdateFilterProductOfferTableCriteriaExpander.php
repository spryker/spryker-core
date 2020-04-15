<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander;

use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface;

class UpdateFilterProductOfferTableCriteriaExpander implements FilterProductOfferTableCriteriaExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface
     */
    protected $tableFilterDataProvider;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface $tableFilterDataProvider
     */
    public function __construct(TableFilterDataProviderInterface $tableFilterDataProvider)
    {
        $this->tableFilterDataProvider = $tableFilterDataProvider;
    }

    /**
     * @param string $filterName
     *
     * @return bool
     */
    public function isApplicable(string $filterName): bool
    {
        return $filterName === $this->tableFilterDataProvider->getFilterData()->getId();
    }

    /**
     * @param mixed $filterValue
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer
     */
    public function expandProductOfferTableCriteria(
        $filterValue,
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): ProductOfferTableCriteriaTransfer {
        $productOfferTableCriteriaTransfer->setUpdatedFrom($filterValue['from'] ?? null);
        $productOfferTableCriteriaTransfer->setUpdatedTo($filterValue['to'] ?? null);

        return $productOfferTableCriteriaTransfer;
    }
}
