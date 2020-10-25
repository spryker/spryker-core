<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch\PaginationConfigBuilder;

use Generated\Shared\Transfer\PaginationConfigTransfer;

class MerchantSearchPaginationConfigBuilder implements PaginationConfigBuilderInterface
{
    /**
     * @var \Generated\Shared\Transfer\PaginationConfigTransfer
     */
    protected $paginationConfigTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaginationConfigTransfer $paginationConfigTransfer
     *
     * @return void
     */
    public function setPaginationConfigTransfer(PaginationConfigTransfer $paginationConfigTransfer): void
    {
        $this->paginationConfigTransfer = $paginationConfigTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PaginationConfigTransfer
     */
    public function getPaginationConfigTransfer(): PaginationConfigTransfer
    {
        return $this->paginationConfigTransfer;
    }

    /**
     * @param array $requestParameters
     *
     * @return int
     */
    public function getCurrentPage(array $requestParameters): int
    {
        $paramName = $this->paginationConfigTransfer
            ->requireParameterName()
            ->getParameterName();

        return isset($requestParameters[$paramName]) ? max((int)$requestParameters[$paramName], 1) : 1;
    }

    /**
     * @param array $requestParameters
     *
     * @return int
     */
    public function getCurrentItemsPerPage(array $requestParameters): int
    {
        $paramName = $this->paginationConfigTransfer->getItemsPerPageParameterName();

        if ($this->isValidItemsPerPage($requestParameters)) {
            return (int)$requestParameters[$paramName];
        }

        return $this->paginationConfigTransfer->getDefaultItemsPerPage();
    }

    /**
     * @param array $requestParameters
     *
     * @return bool
     */
    protected function isValidItemsPerPage(array $requestParameters): bool
    {
        $perPage = $requestParameters[$this->paginationConfigTransfer->getItemsPerPageParameterName()] ?? null;

        return $perPage > 0 && $perPage <= $this->paginationConfigTransfer->getMaxItemsPerPage();
    }
}
