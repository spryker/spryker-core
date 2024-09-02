<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi\Processor\Formatter;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;

interface MultiSelectAttributeFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function formatAbstractMultiSelectAttributesToString(
        AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
    ): AbstractProductsRestAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer
     */
    public function formatConcreteMultiSelectAttributesToString(
        ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
    ): ConcreteProductsRestAttributesTransfer;
}
