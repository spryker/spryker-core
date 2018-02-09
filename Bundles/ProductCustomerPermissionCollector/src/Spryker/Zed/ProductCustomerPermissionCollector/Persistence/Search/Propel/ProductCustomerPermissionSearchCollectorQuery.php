<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermissionCollector\Persistence\Search\Propel;

use Orm\Zed\ProductCustomerPermission\Persistence\Map\SpyProductCustomerPermissionTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

class ProductCustomerPermissionSearchCollectorQuery extends AbstractPropelCollectorQuery
{
    public const FIELD_ID_PRODUCT_CUSTOMER_PERMISSION = 'id_product_customer_permission';
    public const FIELD_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    public const FIELD_FK_CUSTOMER = 'fk_customer';

    /**
     * @return void
     */
    protected function prepareQuery(): void
    {
        $this->touchQuery
            ->addJoin(
                SpyTouchTableMap::COL_ITEM_ID,
                SpyProductCustomerPermissionTableMap::COL_ID_PRODUCT_CUSTOMER_PERMISSION,
                Criteria::INNER_JOIN
            );

        $this->touchQuery
            ->withColumn(SpyProductCustomerPermissionTableMap::COL_ID_PRODUCT_CUSTOMER_PERMISSION, static::FIELD_ID_PRODUCT_CUSTOMER_PERMISSION)
            ->withColumn(SpyProductCustomerPermissionTableMap::COL_FK_PRODUCT_ABSTRACT, static::FIELD_FK_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductCustomerPermissionTableMap::COL_FK_CUSTOMER, static::FIELD_FK_CUSTOMER);
    }
}
