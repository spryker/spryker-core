<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin\Config;

use Generated\Shared\Transfer\PaginationConfigTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\PaginationConfigBuilderInterface;

class PaginationConfigBuilder extends AbstractPlugin implements PaginationConfigBuilderInterface
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

        if ($this->isValidItemsPerPage($paramName, $requestParameters)) {
            return (int)$requestParameters[$paramName];
        }

        return $this->paginationConfigTransfer->getDefaultItemsPerPage();
    }

    /**
     * @param string $paramName
     * @param array $requestParameters
     *
     * @return bool
     */
    protected function isValidItemsPerPage(string $paramName, array $requestParameters): bool
    {
        return (
            !empty($requestParameters[$paramName]) &&
            in_array((int)$requestParameters[$paramName], (array)$this->paginationConfigTransfer->getValidItemsPerPageOptions())
        );
    }
}
