<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Config;

interface SearchConfigCacheSaverInterface
{

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer[] $facetConfigTransfers
     * @param \Generated\Shared\Transfer\SortConfigTransfer[] $sortConfigTransfers
     *
     * @return void
     */
    public function save(array $facetConfigTransfers, array $sortConfigTransfers);

}
