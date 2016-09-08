<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Config;

use Generated\Shared\Transfer\SearchConfigCacheTransfer;

/**
 * @deprecated Provide a list of \Spryker\Client\Search\Dependency\Plugin\SearchConfigExpanderPluginInterface
 * in \Pyz\Client\Search\SearchDependencyProvider::createSearchConfigExpanderPlugins() instead.
 */
interface SearchConfigCacheSaverInterface
{

    /**
     * @param \Generated\Shared\Transfer\SearchConfigCacheTransfer $searchConfigCacheTransfer
     *
     * @return void
     */
    public function save(SearchConfigCacheTransfer $searchConfigCacheTransfer);

}
