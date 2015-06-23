<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Product;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Product\Business\Exception\AbstractProductAttributesExistException;
use SprykerFeature\Zed\Product\Business\Exception\AbstractProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\ConcreteProductAttributesExistException;
use SprykerFeature\Zed\Product\Business\Exception\ConcreteProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
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
     * @param string $sku

     * @return int
     * @throws AbstractProductExistsException
     */
    public function createAbstractProduct($sku);

    /**
     * @param string $sku
     * @return int
     *
     * @throws MissingProductException
     */
    public function getAbstractProductIdBySku($sku);

    /**
     * @param int $idAbstractProduct
     * @param LocaleTransfer $locale
     * @param string $name
     * @param string $attributes
     *
     * @return int
     * @throws AbstractProductAttributesExistException
     */
    public function createAbstractProductAttributes($idAbstractProduct, LocaleTransfer $locale, $name, $attributes);

    /**
     * @param string $sku
     * @param int $idAbstractProduct
     * @param bool $isActive
     *
     * @return int
     * @throws ConcreteProductExistsException
     */
    public function createConcreteProduct($sku, $idAbstractProduct, $isActive = true);

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasConcreteProduct($sku);

    /**
     * @param string $sku
     *
     * @return int
     * @throws MissingProductException
     */
    public function getConcreteProductIdBySku($sku);

    /**
     * @param int $idConcreteProduct
     * @param LocaleTransfer $locale
     * @param string $name
     * @param string $attributes
     *
     * @return int
     * @throws ConcreteProductAttributesExistException
     */
    public function createConcreteProductAttributes($idConcreteProduct, LocaleTransfer $locale, $name, $attributes);

    /**
     * @param int $idAbstractProduct
     */
    public function touchProductActive($idAbstractProduct);

    /**
     * @param string $sku
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @return UrlTransfer
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createProductUrl($sku, $url, LocaleTransfer $locale);

    /**
     * @param int $idAbstractProduct
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @return UrlTransfer
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createProductUrlByIdProduct($idAbstractProduct, $url, LocaleTransfer $locale);

    /**
     * @param string $sku
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @return UrlTransfer
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createAndTouchProductUrl($sku, $url, LocaleTransfer $locale);

    /**
     * @param int $idAbstractProduct
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @return UrlTransfer
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createAndTouchProductUrlByIdProduct($idAbstractProduct, $url, LocaleTransfer $locale);
}
