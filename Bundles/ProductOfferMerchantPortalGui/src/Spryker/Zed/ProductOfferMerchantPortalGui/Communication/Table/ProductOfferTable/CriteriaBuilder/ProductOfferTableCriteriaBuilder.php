<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;

class ProductOfferTableCriteriaBuilder implements ProductOfferTableCriteriaBuilderInterface
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
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface[]
     */
    protected $filterProductOfferTableCriteriaExpanders;

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
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface[] $filterProductOfferTableCriteriaExpanders
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        array $filterProductOfferTableCriteriaExpanders
    ) {
        $this->merchantUserFacade = $merchantUserFacade;
        $this->localeFacade = $localeFacade;
        $this->filterProductOfferTableCriteriaExpanders = $filterProductOfferTableCriteriaExpanders;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer
     */
    public function build(): ProductOfferTableCriteriaTransfer
    {
        $productOfferTableCriteriaTransfer = $this->buildProductOfferTableCriteriaTransfer()
            ->setSearchTerm($this->searchTerm)
            ->setOrderBy($this->sorting)
            ->setPagination($this->buildPaginationTransfer());

        $productOfferTableCriteriaTransfer = $this->addFilterData($productOfferTableCriteriaTransfer);

        return $productOfferTableCriteriaTransfer;
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
     * @return \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer
     */
    protected function buildProductOfferTableCriteriaTransfer(): ProductOfferTableCriteriaTransfer
    {
        $productOfferTableCriteriaTransfer = (new ProductOfferTableCriteriaTransfer())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant())
            ->setIdLocale($this->localeFacade->getCurrentLocale()->getIdLocale());

        return $productOfferTableCriteriaTransfer;
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
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer
     */
    protected function addFilterData(
        ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
    ): ProductOfferTableCriteriaTransfer {
        if (!$this->filters) {
            return $productOfferTableCriteriaTransfer;
        }

        foreach ($this->filters as $filterName => $filterValue) {
            if (!isset($filterValue)) {
                continue;
            }

            $productOfferTableCriteriaExpander = $this->findApplicableProductOfferTableCriteriaExpander($filterName);

            if ($productOfferTableCriteriaExpander) {
                $productOfferTableCriteriaTransfer = $productOfferTableCriteriaExpander->expandProductOfferTableCriteria(
                    $filterValue,
                    $productOfferTableCriteriaTransfer
                );
            }
        }

        return $productOfferTableCriteriaTransfer;
    }

    /**
     * @param string $filterName
     *
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\FilterProductOfferTableCriteriaExpanderInterface|null
     */
    protected function findApplicableProductOfferTableCriteriaExpander(
        string $filterName
    ): ?FilterProductOfferTableCriteriaExpanderInterface {
        foreach ($this->filterProductOfferTableCriteriaExpanders as $filterProductOfferTableCriteriaExpander) {
            if ($filterProductOfferTableCriteriaExpander->isApplicable($filterName)) {
                return $filterProductOfferTableCriteriaExpander;
            }
        }

        return null;
    }
}
