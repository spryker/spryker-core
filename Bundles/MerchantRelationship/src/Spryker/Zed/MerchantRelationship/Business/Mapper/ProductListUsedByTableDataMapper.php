<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Mapper;

use Generated\Shared\Transfer\ButtonCollectionTransfer;
use Generated\Shared\Transfer\ButtonTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableRowTransfer;
use Spryker\Service\UtilText\Model\Url\Url;

class ProductListUsedByTableDataMapper implements ProductListUsedByTableDataMapperInterface
{
    protected const ENTITY_TITLE = 'Merchant Relationship';

    protected const EDIT_BUTTON_TITLE = 'Edit Merchant Relationship';
    protected const EDIT_BUTTON_OPTIONS = [
        'class' => 'btn-edit btn-xs',
        'iconClass' => 'fa fa-pencil-square-o',
    ];

    /**
     * @uses \Spryker\Zed\MerchantRelationshipGui\Communication\Controller\EditMerchantRelationshipController::indexAction()
     */
    protected const ROUTE_EDIT_MERCHANT_RELATIONSHIP = '/merchant-relationship-gui/edit-merchant-relationship';
    protected const PARAM_ID_MERCHANT_RELATIONSHIP = 'id-merchant-relationship';

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\ProductListUsedByTableRowTransfer $productListUsedByTableRowTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableRowTransfer
     */
    public function mapMerchantRelationshipTransferToProductListUsedByTableRowTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ProductListUsedByTableRowTransfer $productListUsedByTableRowTransfer
    ): ProductListUsedByTableRowTransfer {
        $merchantRelationshipTransfer->requireName();
        $merchantRelationshipTransfer->requireIdMerchantRelationship();

        $productListUsedByTableRowTransfer->setEntityTitle(static::ENTITY_TITLE);
        $productListUsedByTableRowTransfer->setEntityName($merchantRelationshipTransfer->getName());
        $productListUsedByTableRowTransfer->setActionButtons(
            $this->createActionButtons($merchantRelationshipTransfer)
        );

        return $productListUsedByTableRowTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    protected function createActionButtons(MerchantRelationshipTransfer $merchantRelationshipTransfer): ButtonCollectionTransfer
    {
        $buttonCollectionTransfer = new ButtonCollectionTransfer();

        $buttonCollectionTransfer = $this->addEditButton($buttonCollectionTransfer, $merchantRelationshipTransfer);

        return $buttonCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    protected function addEditButton(
        ButtonCollectionTransfer $buttonCollectionTransfer,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): ButtonCollectionTransfer {
        $url = Url::generate(static::ROUTE_EDIT_MERCHANT_RELATIONSHIP, [
            static::PARAM_ID_MERCHANT_RELATIONSHIP => $merchantRelationshipTransfer->getIdMerchantRelationship(),
        ])->build();

        $buttonTransfer = (new ButtonTransfer())
            ->setUrl($url)
            ->setTitle(static::EDIT_BUTTON_TITLE)
            ->setDefaultOptions(static::EDIT_BUTTON_OPTIONS);

        return $buttonCollectionTransfer->addButton($buttonTransfer);
    }
}
