<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Product;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ZedProductConcreteTransfer;

interface ProductManagerInterface
{

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdBySku($sku);

    /**
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return int
     */
    public function createProductConcrete(ZedProductConcreteTransfer $productConcreteTransfer, $idProductAbstract);

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku);

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductActive($idProductAbstract);

    /**
     * @param string $sku
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createProductUrl($sku, $url, LocaleTransfer $locale);

    /**
     * @param int $idProductAbstract
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createProductUrlByIdProduct($idProductAbstract, $url, LocaleTransfer $locale);

    /**
     * @param string $sku
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createAndTouchProductUrl($sku, $url, LocaleTransfer $locale);

    /**
     * @param int $idProductAbstract
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createAndTouchProductUrlByIdProduct($idProductAbstract, $url, LocaleTransfer $locale);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return float
     */
    public function getEffectiveTaxRateForProductConcrete($sku);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($sku);

    /**
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function getProductAbstractById($idProductAbstract);

    /**
     * @param int $idProduct
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer|null
     */
    public function getProductConcreteById($idProduct);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return string
     */
    public function getAbstractSkuFromProductConcrete($sku);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $productConcreteCollection
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $productConcreteCollection
     *
     * @throws \Exception
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection);

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer[]
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract);

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributesByAbstractProductId($idProductAbstract);

}
