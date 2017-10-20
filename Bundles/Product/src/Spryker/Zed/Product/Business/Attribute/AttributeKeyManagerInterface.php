<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Attribute;

use Generated\Shared\Transfer\ProductAttributeKeyTransfer;

interface AttributeKeyManagerInterface
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasAttributeKey($key);

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer|null
     */
    public function findAttributeKey($key);

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $attributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function createAttributeKey(ProductAttributeKeyTransfer $attributeKeyTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $attributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function updateAttributeKey(ProductAttributeKeyTransfer $attributeKeyTransfer);
}
