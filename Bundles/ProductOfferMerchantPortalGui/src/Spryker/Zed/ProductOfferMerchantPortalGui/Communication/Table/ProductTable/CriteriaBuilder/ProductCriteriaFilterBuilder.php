<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductCriteriaFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\HasOffersProductTableFilter;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\IsActiveProductTableFilter;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;

class ProductCriteriaFilterBuilder implements ProductCriteriaFilterBuilderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var string|null
     */
    protected $searchTerm;

    /**
     * @var string
     */
    protected $sortColumn;

    /**
     * @var string
     */
    protected $sortDirection;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $pageSize;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductCriteriaFilterTransfer
     */
    public function build(): ProductCriteriaFilterTransfer
    {
        $productCriteriaFilterTransfer = $this->buildProductCriteriaFilterTransfer()
            ->setSearchTerm($this->searchTerm)
            ->setOrderBy($this->sortColumn)
            ->setOrderDirection($this->sortDirection)
            ->setPagination($this->buildPaginationTransfer());

        $productCriteriaFilterTransfer = $this->applyFilters($productCriteriaFilterTransfer);

        return $productCriteriaFilterTransfer;
    }

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage(int $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param int $pageSize
     *
     * @return $this
     */
    public function setPageSize(int $pageSize)
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * @param string|null $searchTerm
     *
     * @return $this
     */
    public function setSearchTerm(?string $searchTerm)
    {
        $this->searchTerm = $searchTerm;

        return $this;
    }

    /**
     * @param string|null $sortColumn
     *
     * @return $this
     */
    public function setSortColumn(?string $sortColumn)
    {
        $this->sortColumn = $sortColumn;

        return $this;
    }

    /**
     * @param string|null $sortDirection
     *
     * @return $this
     */
    public function setSortDirection(?string $sortDirection)
    {
        $this->sortDirection = $sortDirection;

        return $this;
    }

    /**
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductCriteriaFilterTransfer
     */
    protected function buildProductCriteriaFilterTransfer(): ProductCriteriaFilterTransfer
    {
        return (new ProductCriteriaFilterTransfer())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant())
            ->setIdLocale($this->localeFacade->getCurrentLocale()->getIdLocale());
    }

    /**
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function buildPaginationTransfer(): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($this->page)
            ->setMaxPerPage($this->pageSize);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCriteriaFilterTransfer $productCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCriteriaFilterTransfer
     */
    protected function applyFilters(ProductCriteriaFilterTransfer $productCriteriaFilterTransfer): ProductCriteriaFilterTransfer
    {
        if (!$this->filters) {
            return $productCriteriaFilterTransfer;
        }

        if (isset($this->filters[IsActiveProductTableFilter::FILTER_NAME])) {
            $isActive = filter_var($this->filters[IsActiveProductTableFilter::FILTER_NAME], FILTER_VALIDATE_BOOLEAN);
            $productCriteriaFilterTransfer->setIsActive($isActive);
        }

        if (isset($this->filters[HasOffersProductTableFilter::FILTER_NAME])) {
            $hasOffers = filter_var($this->filters[HasOffersProductTableFilter::FILTER_NAME], FILTER_VALIDATE_BOOLEAN);
            $productCriteriaFilterTransfer->setHasOffers($hasOffers);
        }

        return $productCriteriaFilterTransfer;
    }
}
