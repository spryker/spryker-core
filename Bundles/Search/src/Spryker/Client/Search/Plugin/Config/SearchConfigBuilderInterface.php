<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Config;

interface SearchConfigBuilderInterface
{

    /**
     * @param \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface
     *
     * @return void
     */
    function buildFacetConfig(FacetConfigBuilderInterface $facetConfigBuilder);

    /**
     * @param \Spryker\Client\Search\Plugin\Config\SortConfigBuilderInterface
     *
     * @return void
     */
    function buildSortConfig(SortConfigBuilderInterface $sortConfigBuilder);

}
