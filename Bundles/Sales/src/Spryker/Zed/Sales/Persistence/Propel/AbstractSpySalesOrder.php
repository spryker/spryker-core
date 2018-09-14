<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence\Propel;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrder as BaseSpySalesOrder;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotalsQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for representing a row from the 'spy_sales_order' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpySalesOrder extends BaseSpySalesOrder
{
    const COL_FK_CUSTOMER = 'fk_customer';

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderTotals|null
     */
    public function getLastOrderTotals()
    {
        $salesOrderTotalsEntity = SpySalesOrderTotalsQuery::create()
            ->orderByCreatedAt(Criteria::DESC)
            ->filterByFkSalesOrder($this->getIdSalesOrder())
            ->findOne();

        return $salesOrderTotalsEntity;
    }

    /**
     * @deprecated
     *
     * This is for bc reasons, because we don't have database foreign key from fk_customer.
     * Will be removed in the future.
     *
     * @return int|null
     */
    public function getFkCustomer()
    {
        if (property_exists($this, static::COL_FK_CUSTOMER) && !$this->getCustomerReference()) {
            return parent::getFkCustomer();
        }

        if (!$this->getCustomerReference()) {
            return null;
        }

        $idCustomer = SpyCustomerQuery::create()
            ->select([SpyCustomerTableMap::COL_ID_CUSTOMER])
            ->filterByCustomerReference($this->getCustomerReference())
            ->findOne();

        return (int)$idCustomer;
    }

    /**
     * @deprecated
     *
     * Get the associated SpyCustomer object
     *
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con Optional Connection object.
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer|null The associated SpyCustomer object.
     */
    public function getCustomer(?ConnectionInterface $con = null)
    {
        if (property_exists($this, static::COL_FK_CUSTOMER)) {
            return parent::getCustomer($con);
        }

        return SpyCustomerQuery::create()
            ->filterByCustomerReference($this->getCustomerReference())
            ->findOne();
    }

    /**
     * @deprecated
     *
     * This is for bc reasons, because we don't have database foreign key from fk_customer.
     * Will be removed in the future.
     *
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer|null $customerEntity
     *
     * @return $this
     */
    public function setCustomer(?SpyCustomer $customerEntity = null)
    {
        if (property_exists($this, static::COL_FK_CUSTOMER)) {
            parent::setCustomer($customerEntity);
            return $this;
        }

        $this->setCustomerReference($customerEntity->getCustomerReference());

        $customerEntity->save();

        return $this;
    }

    /**
     * Exports the object as an array.
     *
     * This is for BC reasons, because we don't have database foreign key from fk_customer anymore.
     * Will be removed in the future. Please migrate away from requiring this field.
     *
     * Deprecated: Will be removed in the next major. Parent method will be called instead (no code change from usage side).
     *
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_FIELDNAME.
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param bool $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array|string An associative array containing the field names (as keys) and field values
     */
    public function toArray(
        $keyType = TableMap::TYPE_FIELDNAME,
        $includeLazyLoadColumns = true,
        $alreadyDumpedObjects = [],
        $includeForeignObjects = false
    ) {
        if (isset($alreadyDumpedObjects['SpySalesOrder'][$this->hashCode()])) {
            return '*RECURSION*';
        }

        $array = parent::toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, $includeForeignObjects);

        if (!property_exists($this, static::COL_FK_CUSTOMER) || isset($array[SpySalesOrderTableMap::COL_CUSTOMER_REFERENCE])) {
            $idCustomer = $this->getFkCustomer();
            $array[static::COL_FK_CUSTOMER] = $idCustomer;
        }

        return $array;
    }
}
