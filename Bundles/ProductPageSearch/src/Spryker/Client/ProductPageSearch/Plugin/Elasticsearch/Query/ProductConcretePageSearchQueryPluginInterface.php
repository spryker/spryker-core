<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\Query;

use Spryker\Client\Search\Dependency\Plugin\LimitSetterInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchPostponedQueryBuildingInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchStringSetterInterface;

interface ProductConcretePageSearchQueryPluginInterface extends QueryInterface, SearchStringSetterInterface, LimitSetterInterface, SearchPostponedQueryBuildingInterface
{
}
