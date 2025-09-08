<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Client\SelfServicePortal\Builder;

use Generated\Shared\Transfer\PaginationConfigTransfer;

interface PaginationConfigBuilderInterface
{
    public function setPaginationConfigTransfer(PaginationConfigTransfer $paginationConfigTransfer): void;

    public function getPaginationConfigTransfer(): PaginationConfigTransfer;

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
}
