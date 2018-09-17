<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\Query;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Dependency\Plugin\SearchPostponedQueryBuildingInterface;

interface ProductConcretePageSearchQueryPluginInterface extends QueryInterface, SearchPostponedQueryBuildingInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilter
     *
     * @return \Spryker\Client\ProductPageSearch\Plugin\Elasticsearch\Query\ProductConcretePageSearchQueryPluginInterface
     */
    public function setProductConcreteCriteriaFilter(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilter): self;
}
