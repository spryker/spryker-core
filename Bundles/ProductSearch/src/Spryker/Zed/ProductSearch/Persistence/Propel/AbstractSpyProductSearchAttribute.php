<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence\Propel;

use Orm\Zed\ProductSearch\Persistence\Base\SpyProductSearchAttribute as BaseSpyProductSearchAttribute;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeTableMap;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'spy_product_search_attribute' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyProductSearchAttribute extends BaseSpyProductSearchAttribute
{
    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con
     *
     * @return bool
     */
    public function preInsert(?ConnectionInterface $con = null): bool
    {
        $this->presetPosition($con);

        return parent::preInsert($con);
    }

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con
     *
     * @return bool
     */
    public function preUpdate(?ConnectionInterface $con = null): bool
    {
        $this->setSynced(false);

        return parent::preUpdate($con);
    }

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return void
     */
    protected function presetPosition(ConnectionInterface $connection)
    {
        if (!$this->getPosition()) {
            $position = $this->getMaxPosition($connection) + 1;
            $this->setPosition($position);
        }
    }

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     *
     * @return int
     */
    protected function getMaxPosition(ConnectionInterface $connection)
    {
        /** @var \Propel\Runtime\DataFetcher\DataFetcherInterface|\PDOStatement $query */
        $query = $connection
            ->query(sprintf(
                'SELECT MAX(%s) FROM %s',
                SpyProductSearchAttributeTableMap::COL_POSITION,
                SpyProductSearchAttributeTableMap::TABLE_NAME,
            ));

        $maxPosition = $query->fetchColumn();

        return (int)$maxPosition;
    }
}
