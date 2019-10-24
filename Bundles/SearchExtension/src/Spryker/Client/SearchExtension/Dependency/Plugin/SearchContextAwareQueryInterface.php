<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SearchContextTransfer;

interface SearchContextAwareQueryInterface
{
    /**
     * Specification:
     * - Returns SearchContextTransfer which contains context information to be used for a query.
     *
     * @api
     *
     * @deprecated This method will be moved to `\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface`.
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer;

    /**
     * Specification:
     * - Sets SearchContextTransfer which contains context information to be used for a query.
     *
     * @api
     *
     * @deprecated This method will be moved to `\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface`.
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return void
     */
    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void;
}
