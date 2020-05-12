<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder;

use Generated\Shared\Transfer\ProductTableCriteriaTransfer;

interface ProductTableCriteriaBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductTableCriteriaTransfer
     */
    public function build(): ProductTableCriteriaTransfer;

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
     * @param string[] $sorting
     *
     * @return $this
     */
    public function setSorting(array $sorting);

    /**
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters);
}
