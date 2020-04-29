<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder;

use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;

interface ProductOfferTableCriteriaBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer
     */
    public function build(): ProductOfferTableCriteriaTransfer;

    /**
     * @param int $page
     *
     * @return $this
     */
    public function setPage(int $page);

    /**
     * @param int $pageSize
     *
     * @return $this
     */
    public function setPageSize(int $pageSize);

    /**
     * @param string|null $searchTerm
     *
     * @return $this
     */
    public function setSearchTerm(?string $searchTerm);

    /**
     * @param string|null $sortColumn
     *
     * @return $this
     */
    public function setSortColumn(?string $sortColumn);

    /**
     * @param string|null $sortDirection
     *
     * @return $this
     */
    public function setSortDirection(?string $sortDirection);

    /**
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters);
}
