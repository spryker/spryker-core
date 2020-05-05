<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;

class ProductOfferCriteriaFilterBuilder implements ProductOfferCriteriaFilterBuilderInterface
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
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface[]
     */
    protected $productOfferCriteriaFilterExpanders;

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
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface[] $productOfferCriteriaFilterExpanders
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        array $productOfferCriteriaFilterExpanders
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->localeFacade = $localeFacade;
        $this->productOfferCriteriaFilterExpanders = $productOfferCriteriaFilterExpanders;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    public function build(): ProductOfferCriteriaFilterTransfer
    {
        $productOfferCriteriaFilterTransfer = $this->buildProductOfferCriteriaFilterTransfer()
            ->setSearchTerm($this->searchTerm)
            ->setOrderBy($this->sortColumn)
            ->setOrderDirection($this->sortDirection)
            ->setPagination($this->buildPaginationTransfer());

        $productOfferCriteriaFilterTransfer = $this->applyFilters($productOfferCriteriaFilterTransfer);

        return $productOfferCriteriaFilterTransfer;
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
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    protected function buildProductOfferCriteriaFilterTransfer(): ProductOfferCriteriaFilterTransfer
    {
        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant())
            ->setIdLocale($this->localeFacade->getCurrentLocale()->getIdLocale());

        return $productOfferCriteriaFilterTransfer;
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
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    protected function applyFilters(
        ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilterTransfer
    ): ProductOfferCriteriaFilterTransfer {
        if (!$this->filters) {
            return $productOfferCriteriaFilterTransfer;
        }

        foreach ($this->filters as $filterName => $filterValue) {
            if (!isset($filterValue)) {
                continue;
            }

            $productOfferCriteriaFilterExpander = $this->findApplicableProductOfferCriteriaFilterExpander($filterName);

            if ($productOfferCriteriaFilterExpander) {
                $productOfferCriteriaFilterTransfer = $productOfferCriteriaFilterExpander->expandProductOfferCriteriaFilter(
                    $filterValue,
                    $productOfferCriteriaFilterTransfer
                );
            }
        }

        return $productOfferCriteriaFilterTransfer;
    }

    /**
     * @param string $filterName
     *
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface|null
     */
    protected function findApplicableProductOfferCriteriaFilterExpander(
        string $filterName
    ): ?ProductOfferCriteriaFilterExpanderInterface {
        foreach ($this->productOfferCriteriaFilterExpanders as $productOfferCriteriaFilterExpander) {
            if ($productOfferCriteriaFilterExpander->isApplicable($filterName)) {
                return $productOfferCriteriaFilterExpander;
            }
        }

        return null;
    }
}
