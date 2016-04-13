<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Config;

interface SearchConfigInterface
{

    /**
     * @return \Spryker\Client\Search\Plugin\Config\FacetConfigBuilderInterface
     */
    public function getFacetConfigBuilder();

    /**
     * @return \Spryker\Client\Search\Plugin\Config\SortConfigBuilderInterface
     */
    public function getSortConfigBuilder();

}
