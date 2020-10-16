<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\RestLocalizedProductManagementAttributeKeyAttributesTransfer;
use Generated\Shared\Transfer\RestProductManagementAttributeAttributesTransfer;

class ProductAttributeMapper implements ProductAttributeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param \Generated\Shared\Transfer\RestProductManagementAttributeAttributesTransfer $restProductManagementAttributeAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductManagementAttributeAttributesTransfer
     */
    public function mapProductManagementAttributeToRestProductManagementAttributes(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        RestProductManagementAttributeAttributesTransfer $restProductManagementAttributeAttributesTransfer
    ): RestProductManagementAttributeAttributesTransfer {
        $restProductManagementAttributeAttributesTransfer
            ->fromArray($productManagementAttributeTransfer->toArray(), true)
            ->setLocalizedKeys($this->getRestLocalizedProductManagementAttributeKeyAttributes($productManagementAttributeTransfer));

        return $restProductManagementAttributeAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestLocalizedProductManagementAttributeKeyAttributesTransfer[]
     */
    protected function getRestLocalizedProductManagementAttributeKeyAttributes(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ArrayObject {
        $restLocalizedProductManagementAttributeKeyAttributesTransfers = new ArrayObject();

        foreach ($productManagementAttributeTransfer->getLocalizedKeys() as $localizedKey) {
            $restLocalizedProductManagementAttributeKeyAttributesTransfer = (new RestLocalizedProductManagementAttributeKeyAttributesTransfer())
                ->fromArray($localizedKey->toArray(), true)
                ->setTranslation($localizedKey->getKeyTranslation());

            $restLocalizedProductManagementAttributeKeyAttributesTransfers->append($restLocalizedProductManagementAttributeKeyAttributesTransfer);
        }

        return $restLocalizedProductManagementAttributeKeyAttributesTransfers;
    }
}
