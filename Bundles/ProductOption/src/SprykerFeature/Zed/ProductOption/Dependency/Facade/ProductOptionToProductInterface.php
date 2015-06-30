<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\ProductOption\Dependency\Facade;

use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;

interface ProductOptionToProductInterface
{

    /**
     * @param string $sku
     *
     * @return int
     * @throws MissingProductException
     */
    public function getConcreteProductIdBySku($sku);

    /**
     * @param string $sku
     *
     * @return int
     * @throws MissingProductException
     */
    public function getAbstractProductIdByConcreteSku($sku);

    /**
     * @param int $idAbstractProduct
     */
    public function touchProductActive($idAbstractProduct);
}
