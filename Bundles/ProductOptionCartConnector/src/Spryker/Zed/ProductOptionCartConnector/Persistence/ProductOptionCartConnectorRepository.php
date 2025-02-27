<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Persistence;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOptionCartConnector\Persistence\ProductOptionCartConnectorPersistenceFactory getFactory()
 */
class ProductOptionCartConnectorRepository extends AbstractRepository implements ProductOptionCartConnectorRepositoryInterface
{
    /**
     * @module ProductOption
     *
     * @param list<int> $productOptionValueIds
     *
     * @return list<int>
     */
    public function filterProductOptionValueIdsByActiveGroup(array $productOptionValueIds): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productOptionValueCollection */
        $productOptionValueCollection = $this->getFactory()
            ->getProductOptionValuePropelQuery()
            ->filterByIdProductOptionValue_In($productOptionValueIds)
            ->useSpyProductOptionGroupQuery()
                ->filterByActive(true)
            ->endUse()
            ->select(SpyProductOptionValueTableMap::COL_ID_PRODUCT_OPTION_VALUE)
            ->find();

        return $productOptionValueCollection->toArray();
    }
}
