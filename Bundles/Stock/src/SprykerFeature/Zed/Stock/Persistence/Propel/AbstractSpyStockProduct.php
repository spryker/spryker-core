<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Stock\Persistence\Propel;

use Propel\Runtime\Exception\PropelException;
use Orm\Zed\Stock\Persistence\Base\SpyStockProduct as BaseSpyStockProduct;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Propel\Runtime\Propel;

/**
 * Skeleton subclass for representing a row from the 'spy_stock_product' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
abstract class AbstractSpyStockProduct extends BaseSpyStockProduct
{

    /**
     * @param int $amount
     * @throws PropelException
     */
    public function decrement($amount = 1)
    {
        $this->setQuantity($this->getQuantity() - $amount);
        $this->save();
    }

    /**
     * @param int $amount
     * @throws PropelException
     */
    public function increment($amount = 1)
    {
        $this->setQuantity($this->getQuantity() + $amount);
        $this->save();
    }
} // SprykerFeature\Zed\Stock\Persistence\Propel\AbstractSpyStockProduct
