<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Config;

use Generated\Shared\Transfer\PaginationConfigTransfer;

class PaginationConfig implements PaginationConfigInterface
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
    public function setPagination(PaginationConfigTransfer $paginationConfigTransfer): void
    {
        $this->paginationConfigTransfer = $paginationConfigTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PaginationConfigTransfer
     */
    public function get(): PaginationConfigTransfer
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
        $parameterName = $this->paginationConfigTransfer
            ->requireParameterName()
            ->getParameterName();

        return isset($requestParameters[$parameterName]) ? max((int)$requestParameters[$parameterName], 1) : 1;
    }

    /**
     * @param array $requestParameters
     *
     * @return int
     */
    public function getCurrentItemsPerPage(array $requestParameters): int
    {
        $itemsPerPageParameterName = $this->paginationConfigTransfer->getItemsPerPageParameterName();

        if ($this->isValidItemsPerPage($itemsPerPageParameterName, $requestParameters)) {
            return (int)$requestParameters[$itemsPerPageParameterName];
        }

        return $this->paginationConfigTransfer->getDefaultItemsPerPage();
    }

    /**
     * @param string $itemsPerPageParameterName
     * @param array $requestParameters
     *
     * @return bool
     */
    protected function isValidItemsPerPage(string $itemsPerPageParameterName, array $requestParameters): bool
    {
        $itemsPerPage = $requestParameters[$itemsPerPageParameterName] ?? 0;

        return ($itemsPerPage && in_array((int)$itemsPerPage, (array)$this->paginationConfigTransfer->getValidItemsPerPageOptions()));
    }
}
