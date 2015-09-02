<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Product;

use Generated\Shared\Product\AbstractProductInterface;
use Generated\Shared\Product\ConcreteProductInterface;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Product\Business\Exception\AbstractProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\ConcreteProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

interface ProductManagerInterface
{

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasAbstractProduct($sku);

    /**
     * @param AbstractProductInterface $abstractProductTransfer
     *
     * @throws AbstractProductExistsException
     *
     * @return int
     */
    public function createAbstractProduct(AbstractProductInterface $abstractProductTransfer);

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getAbstractProductIdBySku($sku);

    /**
     * @param ConcreteProductInterface $concreteProductTransfer
     * @param int $idAbstractProduct
     *
     * @throws ConcreteProductExistsException
     *
     * @return int
     */
    public function createConcreteProduct(ConcreteProductInterface $concreteProductTransfer, $idAbstractProduct);

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasConcreteProduct($sku);

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getConcreteProductIdBySku($sku);

    /**
     * @param int $idAbstractProduct
     */
    public function touchProductActive($idAbstractProduct);

    /**
     * @param string $sku
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     *
     * @return UrlTransfer
     */
    public function createProductUrl($sku, $url, LocaleTransfer $locale);

    /**
     * @param int $idAbstractProduct
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     *
     * @return UrlTransfer
     */
    public function createProductUrlByIdProduct($idAbstractProduct, $url, LocaleTransfer $locale);

    /**
     * @param string $sku
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     *
     * @return UrlTransfer
     */
    public function createAndTouchProductUrl($sku, $url, LocaleTransfer $locale);

    /**
     * @param int $idAbstractProduct
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     *
     * @return UrlTransfer
     */
    public function createAndTouchProductUrlByIdProduct($idAbstractProduct, $url, LocaleTransfer $locale);

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return float
     */
    public function getEffectiveTaxRateForConcreteProduct($sku);

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getAbstractProductIdByConcreteSku($sku);

    /**
     * @param string $sku
     *
     * @return string
     * @throws MissingProductException
     */
    public function getAbstractSkuFromConcreteProduct($sku);

    /**
     * @param string $concreteSku
     *
     * @return ConcreteProductInterface
     */
    public function getConcreteProduct($concreteSku);

    /**
     * @param $term
     * @param LocaleTransfer $locale
     *
     * @return SpyAbstractProduct[]
     */
    public function getAbstractProductsBySearchTerm($term, LocaleTransfer $locale);

}
