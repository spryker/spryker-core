<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence\Propel;

use Orm\Zed\ProductSearch\Persistence\Base\SpyProductSearchAttributeMap as BaseSpyProductSearchAttributeMap;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'spy_product_search_attribute_map' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyProductSearchAttributeMap extends BaseSpyProductSearchAttributeMap
{
    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $connection
     *
     * @return bool
     */
    public function preUpdate(?ConnectionInterface $connection = null)
    {
        $this->setSynced(false);

        return parent::preUpdate($connection);
    }
}
