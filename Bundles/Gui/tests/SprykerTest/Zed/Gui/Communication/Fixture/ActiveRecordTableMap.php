<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Gui\Communication\Fixture;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Map\TableMap;

class ActiveRecordTableMap extends TableMap
{
    use InstancePoolTrait;

    /**
     * The table name for this class
     *
     * @var string
     */
    public const TABLE_NAME = 'spy_customer';

    /**
     * The related Propel class for this table
     *
     * @var string
     */
    public const OM_CLASS = '\\Orm\\Zed\\Customer\\Persistence\\SpyCustomer';

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->addColumn('first_name', 'FirstName', 'VARCHAR', false, 100, null);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::TABLE_NAME;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return "\SprykerTest\Zed\Gui\Communication\Fixture\ActiveRecord";
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria Object containing the columns to add.
     * @param string|null $alias Optional table alias
     *
     * @return void
     */
    public static function addSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        $criteria->addSelectColumn(static::TABLE_NAME . '.id_customer');
        $criteria->addSelectColumn(static::TABLE_NAME . '.fk_locale');
        $criteria->addSelectColumn(static::TABLE_NAME . '.fk_user');
        $criteria->addSelectColumn(static::TABLE_NAME . '.anonymized_at');
        $criteria->addSelectColumn(static::TABLE_NAME . '.company');
        $criteria->addSelectColumn(static::TABLE_NAME . '.customer_reference');
        $criteria->addSelectColumn(static::TABLE_NAME . '.date_of_birth');
        $criteria->addSelectColumn(static::TABLE_NAME . '.default_billing_address');
        $criteria->addSelectColumn(static::TABLE_NAME . '.default_shipping_address');
        $criteria->addSelectColumn(static::TABLE_NAME . '.email');
        $criteria->addSelectColumn(static::TABLE_NAME . '.first_name');
        $criteria->addSelectColumn(static::TABLE_NAME . '.gender');
        $criteria->addSelectColumn(static::TABLE_NAME . '.last_name');
        $criteria->addSelectColumn(static::TABLE_NAME . '.password');
        $criteria->addSelectColumn(static::TABLE_NAME . '.phone');
        $criteria->addSelectColumn(static::TABLE_NAME . '.registered');
        $criteria->addSelectColumn(static::TABLE_NAME . '.registration_key');
        $criteria->addSelectColumn(static::TABLE_NAME . '.restore_password_date');
        $criteria->addSelectColumn(static::TABLE_NAME . '.restore_password_key');
        $criteria->addSelectColumn(static::TABLE_NAME . '.salutation');
        $criteria->addSelectColumn(static::TABLE_NAME . '.created_at');
        $criteria->addSelectColumn(static::TABLE_NAME . '.updated_at');
    }

    /**
     * @param array $row Row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
     *
     * @return array (SpyCustomer object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = static::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        $cls = static::OM_CLASS;
        $obj = new $cls();
        $col = $obj->hydrate($row, $offset, false, $indexType);
        static::addInstanceToPool($obj, null);

        return [$obj, $col];
    }

    /**
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *
     * @return string|null The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): ?string
    {
        return $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdCustomer', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdCustomer', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdCustomer', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string)$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdCustomer', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IdCustomer', TableMap::TYPE_PHPNAME, $indexType)];
    }
}
