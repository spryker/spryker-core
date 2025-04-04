<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\Customer\Persistence\Propel;

use Orm\Zed\Customer\Persistence\Base\SpyCustomerAddressQuery as BaseSpyCustomerAddressQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for performing query and update operations on the 'spy_customer_address' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyCustomerAddressQuery extends BaseSpyCustomerAddressQuery
{
    protected static bool $withAnonymized = false;

    /**
     * @param string|null $modelAlias
     * @param \Propel\Runtime\ActiveQuery\Criteria|null $criteria
     * @param bool $withAnonymized
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null, bool $withAnonymized = false): Criteria
    {
        $query = parent::create($modelAlias, $criteria);

        static::$withAnonymized = $withAnonymized;

        return $query;
    }

    /**
     * This will automatically add the filterByAnonymizedAt(null) to the query.
     *
     * It MUST be added as late as possible to allow using more relevant and more performant conditions first.
     * `filterById` or `filterByFk` have to be before the anonymized filter to have faster queries.
     *
     * @param \Propel\Runtime\Connection\ConnectionInterface $con
     *
     * @return void
     */
    protected function preSelect(ConnectionInterface $con): void
    {
        if (static::$withAnonymized) {
            return;
        }

        $this->filterByAnonymizedAt(null);
    }
}
