<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Client\SelfServicePortal\Builder;

use Generated\Shared\Transfer\PaginationConfigTransfer;

class SspAssetSearchPaginationConfigBuilder implements PaginationConfigBuilderInterface
{
    /**
     * @var \Generated\Shared\Transfer\PaginationConfigTransfer
     */
    protected PaginationConfigTransfer $paginationConfigTransfer;

    public function setPaginationConfigTransfer(PaginationConfigTransfer $paginationConfigTransfer): void
    {
        $this->paginationConfigTransfer = $paginationConfigTransfer;
    }

    public function getPaginationConfigTransfer(): PaginationConfigTransfer
    {
        return $this->paginationConfigTransfer;
    }

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return int
     */
    public function getCurrentPage(array $requestParameters): int
    {
        $paramName = $this->paginationConfigTransfer->getParameterNameOrFail();

        return isset($requestParameters[$paramName]) ? max((int)$requestParameters[$paramName], 1) : 1;
    }

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return int
     */
    public function getCurrentItemsPerPage(array $requestParameters): int
    {
        $paramName = $this->paginationConfigTransfer->getItemsPerPageParameterName();

        if ($this->isValidItemsPerPage($requestParameters)) {
            return (int)$requestParameters[$paramName];
        }

        return $this->paginationConfigTransfer->getDefaultItemsPerPageOrFail();
    }

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return bool
     */
    protected function isValidItemsPerPage(array $requestParameters): bool
    {
        $perPage = $requestParameters[$this->paginationConfigTransfer->getItemsPerPageParameterName()] ?? null;

        return $perPage > 0 && $perPage <= $this->paginationConfigTransfer->getMaxItemsPerPage();
    }
}
