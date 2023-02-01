<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\RestLocalizedProductAttributeKeyBackendAttributesTransfer;
use Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer;

class ProductAttributeMapper implements ProductAttributeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param \Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer
     */
    public function mapProductManagementAttributeTransferToRestProductAttributesBackendAttributesTransfer(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer
    ): RestProductAttributesBackendAttributesTransfer {
        $restProductAttributesBackendAttributesTransfer
            ->fromArray($productManagementAttributeTransfer->toArray(), true)
            ->setLocalizedKeys($this->getRestLocalizedProductAttributeKeyBackendAttributesTransfers($productManagementAttributeTransfer));

        return $restProductAttributesBackendAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function mapRestProductAttributesBackendAttributesTransferToProductManagementAttributeTransfer(
        RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer,
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ProductManagementAttributeTransfer {
        return $productManagementAttributeTransfer
            ->fromArray($restProductAttributesBackendAttributesTransfer->modifiedToArray(), true)
            ->setLocalizedKeys($this->getLocalizedProductManagementAttributeKeyTransfers($restProductAttributesBackendAttributesTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\RestLocalizedProductAttributeKeyBackendAttributesTransfer>
     */
    protected function getRestLocalizedProductAttributeKeyBackendAttributesTransfers(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ArrayObject {
        $restLocalizedProductAttributeKeyBackendAttributesTransfers = new ArrayObject();

        foreach ($productManagementAttributeTransfer->getLocalizedKeys() as $localizedProductManagementAttributeKeyTransfer) {
            $restLocalizedProductAttributeKeyBackendAttributesTransfer = $this->mapLocalizedProductManagementAttributeKeyTransferToRestLocalizedProductAttributeKeyBackendAttributesTransfer(
                $localizedProductManagementAttributeKeyTransfer,
                new RestLocalizedProductAttributeKeyBackendAttributesTransfer(),
            );

            $restLocalizedProductAttributeKeyBackendAttributesTransfers->append($restLocalizedProductAttributeKeyBackendAttributesTransfer);
        }

        return $restLocalizedProductAttributeKeyBackendAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer>
     */
    protected function getLocalizedProductManagementAttributeKeyTransfers(
        RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer
    ): ArrayObject {
        $localizedProductManagementAttributeKeyTransfers = new ArrayObject();

        foreach ($restProductAttributesBackendAttributesTransfer->getLocalizedKeys() as $restLocalizedProductAttributeKeyBackendAttributesTransfer) {
            $localizedProductManagementAttributeKeyTransfer = $this->mapRestLocalizedProductAttributeKeyBackendAttributesTransferToLocalizedProductManagementAttributeKeyTransfer(
                $restLocalizedProductAttributeKeyBackendAttributesTransfer,
                new LocalizedProductManagementAttributeKeyTransfer(),
            );

            $localizedProductManagementAttributeKeyTransfers->append($localizedProductManagementAttributeKeyTransfer);
        }

        return $localizedProductManagementAttributeKeyTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer $localizedProductManagementAttributeKeyTransfer
     * @param \Generated\Shared\Transfer\RestLocalizedProductAttributeKeyBackendAttributesTransfer $restLocalizedProductAttributeKeyBackendAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestLocalizedProductAttributeKeyBackendAttributesTransfer
     */
    protected function mapLocalizedProductManagementAttributeKeyTransferToRestLocalizedProductAttributeKeyBackendAttributesTransfer(
        LocalizedProductManagementAttributeKeyTransfer $localizedProductManagementAttributeKeyTransfer,
        RestLocalizedProductAttributeKeyBackendAttributesTransfer $restLocalizedProductAttributeKeyBackendAttributesTransfer
    ): RestLocalizedProductAttributeKeyBackendAttributesTransfer {
        return $restLocalizedProductAttributeKeyBackendAttributesTransfer
            ->fromArray($localizedProductManagementAttributeKeyTransfer->toArray(), true)
            ->setTranslation($localizedProductManagementAttributeKeyTransfer->getKeyTranslation());
    }

    /**
     * @param \Generated\Shared\Transfer\RestLocalizedProductAttributeKeyBackendAttributesTransfer $restLocalizedProductAttributeKeyBackendAttributesTransfer
     * @param \Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer $localizedProductManagementAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer
     */
    protected function mapRestLocalizedProductAttributeKeyBackendAttributesTransferToLocalizedProductManagementAttributeKeyTransfer(
        RestLocalizedProductAttributeKeyBackendAttributesTransfer $restLocalizedProductAttributeKeyBackendAttributesTransfer,
        LocalizedProductManagementAttributeKeyTransfer $localizedProductManagementAttributeKeyTransfer
    ): LocalizedProductManagementAttributeKeyTransfer {
        return $localizedProductManagementAttributeKeyTransfer
            ->fromArray($restLocalizedProductAttributeKeyBackendAttributesTransfer->toArray(), true)
            ->setKeyTranslation($restLocalizedProductAttributeKeyBackendAttributesTransfer->getTranslation());
    }
}
