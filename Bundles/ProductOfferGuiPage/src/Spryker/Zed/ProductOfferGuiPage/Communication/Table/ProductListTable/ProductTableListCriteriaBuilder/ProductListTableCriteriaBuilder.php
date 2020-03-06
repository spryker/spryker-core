<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\ProductTableListCriteriaBuilder;

use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductListTableCriteriaTransfer;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\CategoryProductListTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\IsActiveProductListTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\StoresProductListTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToUserFacadeInterface;

class ProductListTableCriteriaBuilder implements ProductListTableCriteriaBuilderInterface
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
     * @return \Generated\Shared\Transfer\ProductListTableCriteriaTransfer
     */
    public function build(): ProductListTableCriteriaTransfer
    {
        $productListTableCriteriaTransfer = $this->buildProductListTableCriteriaTransfer();
        $productListTableCriteriaTransfer->setSearchTerm($this->searchTerm);
        $productListTableCriteriaTransfer->setOrderBy($this->sorting);
        $productListTableCriteriaTransfer->setPagination($this->buildPaginationTransfer());
        $productListTableCriteriaTransfer = $this->addFilterDataToProductListTableCriteriaTransfer($productListTableCriteriaTransfer);

        return $productListTableCriteriaTransfer;
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
     * @return \Generated\Shared\Transfer\ProductListTableCriteriaTransfer
     */
    protected function buildProductListTableCriteriaTransfer(): ProductListTableCriteriaTransfer
    {
        $productListTableCriteriaTransfer = new ProductListTableCriteriaTransfer();
        $productListTableCriteriaTransfer->setMerchantUser($this->getCurrentMerchantUser());
        $productListTableCriteriaTransfer->setLocale($this->localeFacade->getCurrentLocale());

        return $productListTableCriteriaTransfer;
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
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTableCriteriaTransfer
     */
    protected function addFilterDataToProductListTableCriteriaTransfer(ProductListTableCriteriaTransfer $productListTableCriteriaTransfer): ProductListTableCriteriaTransfer
    {
        if (!$this->filters) {
            return $productListTableCriteriaTransfer;
        }

        if (array_key_exists(CategoryProductListTableFilterDataProvider::FILTER_NAME, $this->filters)) {
            $productListTableCriteriaTransfer->setInCategories(
                $this->filters[CategoryProductListTableFilterDataProvider::FILTER_NAME]
            );
        }

        if (array_key_exists(IsActiveProductListTableFilterDataProvider::FILTER_NAME, $this->filters)) {
            $productListTableCriteriaTransfer->setIsActive(true);
        }

        if (array_key_exists(StoresProductListTableFilterDataProvider::FILTER_NAME, $this->filters)) {
            $productListTableCriteriaTransfer->setInStores(
                $this->filters[StoresProductListTableFilterDataProvider::FILTER_NAME]
            );
        }

        return $productListTableCriteriaTransfer;
    }
}
