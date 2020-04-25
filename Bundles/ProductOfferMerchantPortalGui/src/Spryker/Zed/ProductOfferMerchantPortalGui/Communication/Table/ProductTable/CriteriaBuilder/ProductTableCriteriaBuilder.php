<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\HasOffersProductTableFilter;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\IsActiveProductTableFilter;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;

class ProductTableCriteriaBuilder implements ProductTableCriteriaBuilderInterface
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
     * @var array
     */
    protected $sorting;

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
     * @return \Generated\Shared\Transfer\ProductTableCriteriaTransfer
     */
    public function build(): ProductTableCriteriaTransfer
    {
        $productTableCriteriaTransfer = $this->buildProductTableCriteriaTransfer()
            ->setSearchTerm($this->searchTerm)
            ->setOrderBy($this->sorting)
            ->setPagination($this->buildPaginationTransfer());

        $productTableCriteriaTransfer = $this->applyFilters($productTableCriteriaTransfer);

        return $productTableCriteriaTransfer;
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
     * @param string[] $sorting
     *
     * @return $this
     */
    public function setSorting(array $sorting)
    {
        $this->sorting = $sorting;

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
     * @return \Generated\Shared\Transfer\ProductTableCriteriaTransfer
     */
    protected function buildProductTableCriteriaTransfer(): ProductTableCriteriaTransfer
    {
        $productTableCriteriaTransfer = (new ProductTableCriteriaTransfer())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant())
            ->setIdLocale($this->localeFacade->getCurrentLocale()->getIdLocale());

        return $productTableCriteriaTransfer;
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
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableCriteriaTransfer
     */
    protected function applyFilters(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductTableCriteriaTransfer
    {
        if (!$this->filters) {
            return $productTableCriteriaTransfer;
        }

        if (isset($this->filters[IsActiveProductTableFilter::FILTER_NAME])) {
            $isActive = (bool)$this->filters[IsActiveProductTableFilter::FILTER_NAME];
            $productTableCriteriaTransfer->setIsActive($isActive);
        }

        if (isset($this->filters[HasOffersProductTableFilter::FILTER_NAME])) {
            $hasOffers = (bool)$this->filters[HasOffersProductTableFilter::FILTER_NAME];
            $productTableCriteriaTransfer->setHasOffers($hasOffers);
        }

        return $productTableCriteriaTransfer;
    }
}
