<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Stock\Persistence\Propel;

use SprykerFeature\Zed\Stock\Persistence\Propel\Base\SpyStockProduct as BaseSpyStockProduct;
use SprykerFeature\Zed\Stock\Persistence\Propel\Map\SpyStockProductTableMap;
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
class SpyStockProduct extends BaseSpyStockProduct
{

    /**
     * If new just decrement value in entity
     * otherwise execute atomar query and reload entity
     * @param int $amount
     */
    public function decrement($amount = 1)
    {
        if ($this->isNew()) {
            $this->setQuantity($this->getQuantity()-$amount);
            $this->save();
        } else {
            $con = Propel::getConnection();
            $statement = $con->prepare(
                'UPDATE '
                . SpyStockProductTableMap::TABLE_NAME
                . ' SET ' . SpyStockProductTableMap::COL_QUANTITY . '='
                . SpyStockProductTableMap::COL_QUANTITY . '-:quantity WHERE '
                . SpyStockProductTableMap::COL_ID_STOCK_PRODUCT . '=:id_stock_product'
            );

            $idStockProduct = $this->getIdStockProduct();
            $statement->bindParam(':quantity', $amount, \PDO::PARAM_INT);
            $statement->bindParam(':id_stock_product', $idStockProduct, \PDO::PARAM_INT);
            $statement->execute();
            $statement->closeCursor();
            $this->reload();
        }
    }

    /**
     * If new just increment value in entity
     * otherwise execute atomar query and reload entity
     * @param int $amount
     */
    public function increment($amount = 1)
    {
        if ($this->isNew()) {
            $this->setQuantity($this->getQuantity()+$amount);
            $this->save();
        } else {
            $con = Propel::getConnection();
            $statement = $con->prepare(
                'UPDATE ' . SpyStockProductTableMap::TABLE_NAME
                . ' SET ' . SpyStockProductTableMap::COL_QUANTITY . '='
                . SpyStockProductTableMap::COL_QUANTITY . '+:quantity WHERE '
                . SpyStockProductTableMap::COL_ID_STOCK_PRODUCT . '=:id_stock_product'
            );

            $idStockProduct = $this->getIdStockProduct();
            $statement->bindParam(':quantity', $amount, \PDO::PARAM_INT);
            $statement->bindParam(':id_stock_product', $idStockProduct, \PDO::PARAM_INT);
            $statement->execute();
            $statement->closeCursor();
            $this->reload();
        }

    }
} // SprykerFeature\Zed\Stock\Persistence\Propel\SpyStockProduct
