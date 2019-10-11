<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer;
use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormFileUploadHandlerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGui\Communication\Form\ProductListProductConcreteRelationFormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ProductConcreteRelationCsvConfigurableBundleTemplateSlotEditFormFileUploadHandlerPlugin extends AbstractPlugin implements ConfigurableBundleTemplateSlotEditFormFileUploadHandlerPluginInterface
{
    protected const FIELD_PATH_SEPARATOR = '.';

    /**
     * {@inheritDoc}
     * - Handles Product Concrete Relation file upload.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer
     */
    public function handleFileUpload(UploadedFile $uploadedFile, ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer): ConfigurableBundleTemplateSlotEditFormTransfer
    {
        $productListAggregateFormTransfer = $configurableBundleTemplateSlotEditFormTransfer->getProductListAggregateForm();

        if (!$productListAggregateFormTransfer) {
            return $configurableBundleTemplateSlotEditFormTransfer;
        }

        $productListAggregateFormTransfer->setProductListProductConcreteRelation(
            $this->getFactory()->createProductListImporter()->importFromCsvFile(
                $uploadedFile,
                $productListAggregateFormTransfer->getProductListProductConcreteRelation()
            )
        );

        return $configurableBundleTemplateSlotEditFormTransfer->setProductListAggregateForm($productListAggregateFormTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns Product Concrete Relation field path.
     *
     * @api
     *
     * @return string
     */
    public function getFieldPath(): string
    {
        return implode(static::FIELD_PATH_SEPARATOR, [
            ProductListAggregateFormTransfer::PRODUCT_LIST_PRODUCT_CONCRETE_RELATION,
            ProductListProductConcreteRelationFormType::FIELD_FILE_UPLOAD,
        ]);
    }
}
