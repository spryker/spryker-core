<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter\UpdatedAtProductOfferTableFilter;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface;

class UpdatedAtProductOfferCriteriaFilterExpander implements ProductOfferCriteriaFilterExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface $utilDateTimeService)
    {
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param string $filterName
     *
     * @return bool
     */
    public function isApplicable(string $filterName): bool
    {
        return $filterName === UpdatedAtProductOfferTableFilter::FILTER_NAME;
    }

    /**
     * @param mixed $filterValue
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    public function expandProductOfferCriteriaFilter(
        $filterValue,
        ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer
    ): ProductOfferCriteriaFilterTransfer {
        $productOfferCriteriaFilterTransfer->setUpdatedFrom(
            isset($filterValue['from']) ? $this->utilDateTimeService->formatToDbDateTime($filterValue['from']) : null
        );
        $productOfferCriteriaFilterTransfer->setUpdatedTo(
            isset($filterValue['to']) ? $this->utilDateTimeService->formatToDbDateTime($filterValue['to']) : null
        );

        return $productOfferCriteriaFilterTransfer;
    }
}
