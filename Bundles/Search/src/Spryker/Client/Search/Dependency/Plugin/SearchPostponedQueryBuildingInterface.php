<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

use Elastica\Query;

interface SearchPostponedQueryBuildingInterface
{
    /**
     * @api
     *
     * @return \Elastica\Query
     */
    public function buildQuery(): Query;
}
