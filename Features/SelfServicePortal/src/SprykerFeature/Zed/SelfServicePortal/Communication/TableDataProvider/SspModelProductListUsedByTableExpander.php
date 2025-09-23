<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\TableDataProvider;

use Generated\Shared\Transfer\ButtonCollectionTransfer;
use Generated\Shared\Transfer\ButtonTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableRowTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableTransfer;
use Generated\Shared\Transfer\SspModelCollectionTransfer;
use Generated\Shared\Transfer\SspModelConditionsTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SspModelProductListUsedByTableExpander implements SspModelProductListUsedByTableExpanderInterface
{
    /**
     * @var string
     */
    protected const BUTTON_TITLE_EDIT_MODEL = 'Edit Model';

    /**
     * @var string
     */
    protected const TITLE_MODEL = 'Model';

    /**
     * @var string
     */
    protected const BUTTON_URL_EDIT_MODEL = '/self-service-portal/update-model?id-ssp-model=%d';

    /**
     * @var string
     */
    protected const BUTTON_CLASS_EDIT_MODEL = 'btn-edit';

    public function __construct(protected SelfServicePortalRepositoryInterface $selfServicePortalRepository)
    {
    }

    public function expandTableData(ProductListUsedByTableTransfer $productListUsedByTableTransfer): ProductListUsedByTableTransfer
    {
        $productListTransfer = $productListUsedByTableTransfer->getProductListOrFail();
        $idProductList = $productListTransfer->getIdProductListOrFail();

        $sspModelIds = $this->selfServicePortalRepository->getSspModelIdsByProductListId($idProductList);

        if (!$sspModelIds) {
            return $productListUsedByTableTransfer;
        }

        $sspModelCollectionTransfer = $this->getSspModelCollection($sspModelIds);

        foreach ($sspModelCollectionTransfer->getSspModels()->getArrayCopy() as $sspModelTransfer) {
            $productListUsedByTableRowTransfer = $this->createTableRow($sspModelTransfer);
            $productListUsedByTableTransfer->addRow($productListUsedByTableRowTransfer);
        }

        return $productListUsedByTableTransfer;
    }

    /**
     * @param array<int> $sspModelIds
     *
     * @return \Generated\Shared\Transfer\SspModelCollectionTransfer
     */
    protected function getSspModelCollection(array $sspModelIds): SspModelCollectionTransfer
    {
        $sspModelCriteriaTransfer = (new SspModelCriteriaTransfer())
            ->setSspModelConditions(
                (new SspModelConditionsTransfer())
                    ->setSspModelIds($sspModelIds),
            );

        return $this->selfServicePortalRepository->getSspModelCollection($sspModelCriteriaTransfer);
    }

    protected function createTableRow(SspModelTransfer $sspModelTransfer): ProductListUsedByTableRowTransfer
    {
        $editModelButtonTransfer = $this->createEditModelButton($sspModelTransfer);
        $buttonCollectionTransfer = (new ButtonCollectionTransfer())
            ->addButton($editModelButtonTransfer);

        return (new ProductListUsedByTableRowTransfer())
            ->setTitle(static::TITLE_MODEL)
            ->setName($sspModelTransfer->getNameOrFail())
            ->setActionButtons($buttonCollectionTransfer);
    }

    protected function createEditModelButton(SspModelTransfer $sspModelTransfer): ButtonTransfer
    {
        return (new ButtonTransfer())
            ->setTitle(static::BUTTON_TITLE_EDIT_MODEL)
            ->setUrl(sprintf(static::BUTTON_URL_EDIT_MODEL, $sspModelTransfer->getIdSspModelOrFail()))
            ->setDefaultOptions([
                'class' => static::BUTTON_CLASS_EDIT_MODEL,
            ]);
    }
}
