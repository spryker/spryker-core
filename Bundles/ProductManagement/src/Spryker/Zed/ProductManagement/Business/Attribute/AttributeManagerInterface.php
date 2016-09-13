<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ZedProductConcreteTransfer;

interface AttributeManagerInterface
{

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection();

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
     */
    public function getProductAttributeValueCollection();

    /**
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function persistProductAbstractLocalizedAttributes(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function persistProductConcreteLocalizedAttributes(ZedProductConcreteTransfer $productConcreteTransfer);

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
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer[] $concreteProductCollection
     *
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface
     */
    public function buildAttributeProcessor(ProductAbstractTransfer $productAbstractTransfer, array $concreteProductCollection = []);

}
