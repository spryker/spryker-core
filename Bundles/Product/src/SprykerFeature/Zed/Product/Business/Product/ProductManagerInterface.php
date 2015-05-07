<?php

namespace SprykerFeature\Zed\Product\Business\Product;

use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use Generated\Shared\Transfer\UrlUrlTransfer;
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
     * @param LocaleDto $locale
     * @param string $name
     * @param string $attributes
     *
     * @return int
     * @throws AbstractProductAttributesExistException
     */
    public function createAbstractProductAttributes($idAbstractProduct, LocaleDto $locale, $name, $attributes);

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
     * @param LocaleDto $locale
     * @param string $name
     * @param string $attributes
     *
     * @return int
     * @throws ConcreteProductAttributesExistException
     */
    public function createConcreteProductAttributes($idConcreteProduct, LocaleDto $locale, $name, $attributes);

    /**
     * @param int $idAbstractProduct
     */
    public function touchProductActive($idAbstractProduct);

    /**
     * @param string $sku
     * @param string $url
     * @param LocaleDto $locale
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createProductUrl($sku, $url, LocaleDto $locale);

    /**
     * @param int $idAbstractProduct
     * @param string $url
     * @param LocaleDto $locale
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createProductUrlByIdProduct($idAbstractProduct, $url, LocaleDto $locale);

    /**
     * @param string $sku
     * @param string $url
     * @param LocaleDto $locale
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createAndTouchProductUrl($sku, $url, LocaleDto $locale);

    /**
     * @param int $idAbstractProduct
     * @param string $url
     * @param LocaleDto $locale
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createAndTouchProductUrlByIdProduct($idAbstractProduct, $url, LocaleDto $locale);
}
