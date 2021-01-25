<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

/**
 * @deprecated Will be removed without replacement.
 */
interface SearchConfigInterface
{
    /**
     * @api
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface
     */
    public function getFacetConfigBuilder();

    /**
     * @api
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface
     */
    public function getSortConfigBuilder();

    /**
     * @api
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    public function getPaginationConfigBuilder();
}
