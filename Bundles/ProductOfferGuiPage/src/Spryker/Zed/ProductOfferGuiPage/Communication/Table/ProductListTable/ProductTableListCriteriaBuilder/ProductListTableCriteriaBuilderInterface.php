<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\ProductTableListCriteriaBuilder;

use Generated\Shared\Transfer\ProductListTableCriteriaTransfer;

interface ProductListTableCriteriaBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductListTableCriteriaTransfer
     */
    public function build(): ProductListTableCriteriaTransfer;

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
