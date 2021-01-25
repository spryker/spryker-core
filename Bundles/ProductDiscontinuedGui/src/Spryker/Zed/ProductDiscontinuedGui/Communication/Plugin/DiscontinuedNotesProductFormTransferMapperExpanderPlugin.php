<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Communication\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductDiscontinuedGui\Communication\Form\DiscontinueProductForm;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductFormTransferMapperExpanderPluginInterface;

class DiscontinuedNotesProductFormTransferMapperExpanderPlugin implements ProductFormTransferMapperExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * Specification:
     * - Expands ProductConcreteTransfer with discontinued notes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcrete
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function map(ProductConcreteTransfer $productConcrete, array $formData): ProductConcreteTransfer
    {
        if (!empty($formData[DiscontinueProductForm::FIELD_DISCONTINUED_NOTES])) {
            $productConcrete->setDiscontinuedNotes($formData[DiscontinueProductForm::FIELD_DISCONTINUED_NOTES]);
        }

        return $productConcrete;
    }
}
