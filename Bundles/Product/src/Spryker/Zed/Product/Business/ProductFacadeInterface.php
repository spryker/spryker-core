<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

interface ProductFacadeInterface
{

    /**
     * @api
     *
     * @param \SplFileInfo $file
     *
     * @return \Spryker\Zed\Product\Business\Model\ProductBatchResult
     */
    public function importProductsFromFile(\SplFileInfo $file);

    /**
     * @api
     *
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdBySku($sku);

    /**
     * @api
     *
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku);

    /**
     * @api
     *
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($sku);

    /**
     * @api
     *
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return float
     */
    public function getEffectiveTaxRateForProductConcrete($sku);

    /**
     * @api
     *
     * @param string $attributeName
     *
     * @return bool
     */
    public function hasAttribute($attributeName);

    /**
     * @api
     *
     * @param string $attributeType
     *
     * @return bool
     */
    public function hasAttributeType($attributeType);

    /**
     * @api
     *
     * @param string $name
     * @param string $inputType
     * @param int|null $fkParentAttributeType
     *
     * @throws \Spryker\Zed\Product\Business\Exception\AttributeTypeExistsException
     *
     * @return int
     */
    public function createAttributeType($name, $inputType, $fkParentAttributeType = null);

    /**
     * @api
     *
     * @param string $attributeName
     * @param string $attributeType
     * @param bool $isEditable
     *
     * @throws \Spryker\Zed\Product\Business\Exception\AttributeExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\MissingAttributeTypeException
     *
     * @return int
     */
    public function createAttribute($attributeName, $attributeType, $isEditable = true);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param int $idProductAbstract
     *
     * @return int
     */
    public function createProductConcrete(ProductConcreteTransfer $productConcreteTransfer, $idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductActive($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductInactive($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductDeleted($idProductAbstract);

    /**
     * @api
     *
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
     * @api
     *
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
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface|null $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger = null);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return string
     */
    public function getAbstractSkuFromProductConcrete($sku);

    /**
     * @api
     *
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createAndTouchProductUrlByIdProduct($idProductAbstract, $url, LocaleTransfer $locale);

    /**
     * @api
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface
     */
    public function getAttributeManager();

}
