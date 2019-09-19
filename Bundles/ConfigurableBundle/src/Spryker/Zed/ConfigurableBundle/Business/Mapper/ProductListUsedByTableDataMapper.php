<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Mapper;

use Generated\Shared\Transfer\ButtonCollectionTransfer;
use Generated\Shared\Transfer\ButtonTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableRowTransfer;
use Spryker\Service\UtilText\Model\Url\Url;

class ProductListUsedByTableDataMapper implements ProductListUsedByTableDataMapperInterface
{
    protected const ENTITY_TITLE = 'Configurable Bundle Template';

    protected const EDIT_BUTTON_TITLE = 'Edit Slot';
    protected const EDIT_BUTTON_OPTIONS = [
        'class' => 'btn-edit btn-xs',
        'iconClass' => 'fa fa-pencil-square-o',
    ];

    /**
     * @uses \Spryker\Zed\ConfigurableBundleGui\Communication\Controller\SlotController::editAction()
     */
    protected const ROUTE_EDIT_SLOT = '/configurable-bundle-gui/slot/edit';
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'id-configurable-bundle-template-slot';

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     * @param \Generated\Shared\Transfer\ProductListUsedByTableRowTransfer $productListUsedByTableRowTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableRowTransfer
     */
    public function mapConfigurableBundleTemplateSlotTransferToProductListUsedByTableRowTransfer(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer,
        ProductListUsedByTableRowTransfer $productListUsedByTableRowTransfer
    ): ProductListUsedByTableRowTransfer {
        $configurableBundleTemplateSlotTransfer->requireTranslations();
        $configurableBundleTemplateSlotTransfer->getConfigurableBundleTemplate()->requireTranslations();

        $productListUsedByTableRowTransfer->setEntityTitle(static::ENTITY_TITLE);
        $productListUsedByTableRowTransfer->setEntityName(
            $this->createComposedEntityName($configurableBundleTemplateSlotTransfer)
        );
        $productListUsedByTableRowTransfer->setActionButtons(
            $this->createActionButtons($configurableBundleTemplateSlotTransfer)
        );

        return $productListUsedByTableRowTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return string
     */
    protected function createComposedEntityName(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): string
    {
        $configurableBundleTemplateSlotName = $configurableBundleTemplateSlotTransfer->getTranslations()[0]->getName();
        $configurableBundleTemplateName = $configurableBundleTemplateSlotTransfer->getConfigurableBundleTemplate()
            ->getTranslations()[0]
            ->getName();

        return sprintf(
            '%s - %s',
            $configurableBundleTemplateName,
            $configurableBundleTemplateSlotName
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    protected function createActionButtons(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): ButtonCollectionTransfer
    {
        $buttonCollectionTransfer = new ButtonCollectionTransfer();

        $buttonCollectionTransfer = $this->addEditButton($buttonCollectionTransfer, $configurableBundleTemplateSlotTransfer);

        return $buttonCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    protected function addEditButton(
        ButtonCollectionTransfer $buttonCollectionTransfer,
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ButtonCollectionTransfer {
        $url = Url::generate(static::ROUTE_EDIT_SLOT, [
            static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT => $configurableBundleTemplateSlotTransfer->getIdConfigurableBundleTemplateSlot(),
        ])->build();

        $buttonTransfer = (new ButtonTransfer())
            ->setUrl($url)
            ->setTitle(static::EDIT_BUTTON_TITLE)
            ->setDefaultOptions(static::EDIT_BUTTON_OPTIONS);

        return $buttonCollectionTransfer->addButton($buttonTransfer);
    }
}
