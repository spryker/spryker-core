<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

use Generated\Shared\Transfer\PaginationConfigTransfer;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Config\PaginationConfigInterface` instead.
 */
interface PaginationConfigBuilderInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PaginationConfigTransfer $paginationConfigTransfer
     *
     * @return void
     */
    public function setPagination(PaginationConfigTransfer $paginationConfigTransfer);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\PaginationConfigTransfer
     */
    public function get();

    /**
     * @api
     *
     * @param array $requestParameters
     *
     * @return int
     */
    public function getCurrentPage(array $requestParameters);

    /**
     * @api
     *
     * @param array $requestParameters
     *
     * @return int
     */
    public function getCurrentItemsPerPage(array $requestParameters);
}
