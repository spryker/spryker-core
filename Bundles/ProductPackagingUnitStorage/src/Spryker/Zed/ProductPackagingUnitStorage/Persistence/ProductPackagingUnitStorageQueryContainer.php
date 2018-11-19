<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 19.11.18
 * Time: 12:16
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Persistence;

use Orm\Zed\ProductPackagingUnitStorage\Persistence\Map\SpyProductAbstractPackagingStorageTableMap;
use Orm\Zed\ProductPackagingUnitStorage\Persistence\SpyProductAbstractPackagingStorageQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageFacadeInterface;
use Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStoragePersistenceFactory
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStoragePersistenceFactory getFactory()()
 */
class ProductPackagingUnitStorageQueryContainer extends AbstractQueryContainer implements ProductPackagingUnitStorageQueryContainerInterface
{
    public function queryProductAbstractPackagingStorageEntitiesByProductAbstractIds(array $productAbstractIds): AvailabilityStoragePersistenceFactory
    {
        return $this->getFactory()
            ->createSpyProductAbstractPackagingStorageQuery()
            ->filterByFkProductAbstract($productAbstractIds);
    }
}