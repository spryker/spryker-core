<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Attribute;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface AttributeManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function persistProductAbstractLocalizedAttributes(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function persistProductConcreteLocalizedAttributes(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * @param array $data
     * @param string $attributeJson
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    public function createLocalizedAttributesTransfer(array $data, $attributeJson, LocaleTransfer $localeTransfer);

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function encodeAttributes(array $attributes);

    /**
     * @param string $json
     *
     * @return array
     */
    public function decodeAttributes($json);

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $concreteProductCollection
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeProcessorInterface
     */
    public function buildAttributeProcessor(ProductAbstractTransfer $productAbstractTransfer, array $concreteProductCollection = []);

    /**
     * @param array $localizedAttributeCollection
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $default
     *
     * @return string|null
     */
    public function getProductNameFromLocalizedAttributes(array $localizedAttributeCollection, LocaleTransfer $localeTransfer, $default = null);

}
