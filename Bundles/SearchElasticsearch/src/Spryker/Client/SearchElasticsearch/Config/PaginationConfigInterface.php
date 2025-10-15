<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Config;

use Generated\Shared\Transfer\PaginationConfigTransfer;

interface PaginationConfigInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaginationConfigTransfer $paginationConfigTransfer
     *
     * @return void
     */
    public function setPagination(PaginationConfigTransfer $paginationConfigTransfer): void;

    /**
     * @return \Generated\Shared\Transfer\PaginationConfigTransfer
     */
    public function get(): PaginationConfigTransfer;

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return int
     */
    public function getCurrentPage(array $requestParameters): int;

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return int
     */
    public function getCurrentItemsPerPage(array $requestParameters): int;

    public function getMaxItemsInPagination(): int;
}
