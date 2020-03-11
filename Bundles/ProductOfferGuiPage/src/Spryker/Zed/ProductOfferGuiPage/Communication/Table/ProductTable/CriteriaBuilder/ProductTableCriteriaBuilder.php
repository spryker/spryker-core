<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder;

use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\CategoryProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\IsActiveProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\StoresProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToUserFacadeInterface;

class ProductTableCriteriaBuilder implements ProductTableCriteriaBuilderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface
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
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductOfferGuiPageToUserFacadeInterface $userFacade,
        ProductOfferGuiPageToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferGuiPageToLocaleFacadeInterface $localeFacade
    ) {
        $this->userFacade = $userFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductTableCriteriaTransfer
     */
    public function build(): ProductTableCriteriaTransfer
    {
        $productTableCriteriaTransfer = $this->buildProductTableCriteriaTransfer();
        $productTableCriteriaTransfer->setSearchTerm($this->searchTerm);
        $productTableCriteriaTransfer->setOrderBy($this->sorting);
        $productTableCriteriaTransfer->setPagination($this->buildPaginationTransfer());
        $productTableCriteriaTransfer = $this->addFilterData($productTableCriteriaTransfer);

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
        $productTableCriteriaTransfer = new ProductTableCriteriaTransfer();
        $productTableCriteriaTransfer->setMerchantUser($this->getCurrentMerchantUser());
        $productTableCriteriaTransfer->setLocale($this->localeFacade->getCurrentLocale());

        return $productTableCriteriaTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    protected function getCurrentMerchantUser(): ?MerchantUserTransfer
    {
        $currentUserTransfer = $this->userFacade->getCurrentUser();
        $merchantUserCriteriaFilterTransfer = new MerchantUserCriteriaFilterTransfer();
        $merchantUserCriteriaFilterTransfer->setIdUser($currentUserTransfer->getIdUser());

        return $this->merchantUserFacade->findOne($merchantUserCriteriaFilterTransfer);
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
    protected function addFilterData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductTableCriteriaTransfer
    {
        if (!$this->filters) {
            return $productTableCriteriaTransfer;
        }

        if (array_key_exists(CategoryProductTableFilterDataProvider::FILTER_NAME, $this->filters)) {
            $productTableCriteriaTransfer->setInCategories(
                $this->filters[CategoryProductTableFilterDataProvider::FILTER_NAME]
            );
        }

        if (array_key_exists(IsActiveProductTableFilterDataProvider::FILTER_NAME, $this->filters)) {
            $productTableCriteriaTransfer->setIsActive(true);
        }

        if (array_key_exists(StoresProductTableFilterDataProvider::FILTER_NAME, $this->filters)) {
            $productTableCriteriaTransfer->setInStores(
                $this->filters[StoresProductTableFilterDataProvider::FILTER_NAME]
            );
        }

        return $productTableCriteriaTransfer;
    }
}
