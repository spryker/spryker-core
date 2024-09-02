<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi\Processor\Formatter;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;

class MultiSelectAttributeFormatter implements MultiSelectAttributeFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function formatAbstractMultiSelectAttributesToString(
        AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
    ): AbstractProductsRestAttributesTransfer {
        $formattedAttributes = $this->formatMultiSelectAttributesToString(
            $abstractProductsRestAttributesTransfer->getAttributes(),
        );

        $abstractProductsRestAttributesTransfer->setAttributes($formattedAttributes);

        return $abstractProductsRestAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer
     */
    public function formatConcreteMultiSelectAttributesToString(
        ConcreteProductsRestAttributesTransfer $concreteProductsRestAttributesTransfer
    ): ConcreteProductsRestAttributesTransfer {
        $formattedAttributes = $this->formatMultiSelectAttributesToString(
            $concreteProductsRestAttributesTransfer->getAttributes(),
        );

        $concreteProductsRestAttributesTransfer->setAttributes($formattedAttributes);

        return $concreteProductsRestAttributesTransfer;
    }

    /**
     * @param array<mixed> $attributes
     *
     * @return array<string, string>
     */
    protected function formatMultiSelectAttributesToString(array $attributes): array
    {
        $formattedAttributes = [];
        foreach ($attributes as $key => $attribute) {
            if (is_array($attribute)) {
                $formattedAttributes[$key] = implode(', ', $attribute);

                continue;
            }

            $formattedAttributes[$key] = $attribute;
        }

        return $formattedAttributes;
    }
}
