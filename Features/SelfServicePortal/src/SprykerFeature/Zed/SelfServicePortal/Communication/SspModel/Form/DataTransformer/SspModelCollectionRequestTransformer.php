<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form\DataTransformer;

use ArrayObject;
use Generated\Shared\Transfer\ModelProductListAssignmentTransfer;
use Generated\Shared\Transfer\ModelSspAssetAssignmentTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspModelCollectionRequestTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Symfony\Component\Form\DataTransformerInterface;

class SspModelCollectionRequestTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    protected const FORM_FIELD_SSP_ASSET_IDS_TO_BE_ASSIGNED = 'sspAssetIdsToBeAssigned';

    /**
     * @var string
     */
    protected const FORM_FIELD_SSP_ASSET_IDS_TO_BE_UNASSIGNED = 'sspAssetIdsToBeUnassigned';

    /**
     * @var string
     */
    protected const FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_ASSIGNED = 'productListIdsToBeAssigned';

    /**
     * @var string
     */
    protected const FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_UNASSIGNED = 'productListIdsToBeUnassigned';

    public function __construct(protected SspModelTransfer $sspModelTransfer)
    {
    }

    /**
     * @param mixed $value
     *
     * @return array<mixed>
     */
    public function transform($value): array
    {
        if (!$value instanceof SspModelCollectionRequestTransfer) {
            return [];
        }

        return [
            static::FORM_FIELD_SSP_ASSET_IDS_TO_BE_ASSIGNED => $this->extractAssetIds($value->getSspAssetsToBeAssigned()),
            static::FORM_FIELD_SSP_ASSET_IDS_TO_BE_UNASSIGNED => $this->extractAssetIds($value->getSspAssetsToBeUnassigned()),
            static::FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_ASSIGNED => $this->extractProductListIds($value->getProductListsToBeAssigned()),
            static::FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_UNASSIGNED => $this->extractProductListIds($value->getProductListsToBeUnassigned()),
        ];
    }

    /**
     * @param mixed $value
     *
     * @return \Generated\Shared\Transfer\SspModelCollectionRequestTransfer
     */
    public function reverseTransform($value): SspModelCollectionRequestTransfer
    {
        if (!is_array($value)) {
            return new SspModelCollectionRequestTransfer();
        }

        $sspModelCollectionRequestTransfer = new SspModelCollectionRequestTransfer();

        $this->processAssetAssignments($value, $sspModelCollectionRequestTransfer);
        $this->processProductListAssignments($value, $sspModelCollectionRequestTransfer);

        return $sspModelCollectionRequestTransfer;
    }

    /**
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer
     *
     * @return void
     */
    protected function processAssetAssignments(array $formData, SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer): void
    {
        $assetsToAssign = $this->parseIds($formData[static::FORM_FIELD_SSP_ASSET_IDS_TO_BE_ASSIGNED] ?? '');
        $assetsToUnassign = $this->parseIds($formData[static::FORM_FIELD_SSP_ASSET_IDS_TO_BE_UNASSIGNED] ?? '');

        $this->addAssetAssignments($assetsToAssign, $sspModelCollectionRequestTransfer, true);
        $this->addAssetAssignments($assetsToUnassign, $sspModelCollectionRequestTransfer, false);
    }

    /**
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer
     *
     * @return void
     */
    protected function processProductListAssignments(array $formData, SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer): void
    {
        $productListsToAssign = $this->parseIds($formData[static::FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_ASSIGNED] ?? '');
        $productListsToUnassign = $this->parseIds($formData[static::FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_UNASSIGNED] ?? '');

        $this->addProductListAssignments($productListsToAssign, $sspModelCollectionRequestTransfer, true);
        $this->addProductListAssignments($productListsToUnassign, $sspModelCollectionRequestTransfer, false);
    }

    /**
     * @param string $idsString
     *
     * @return array<int>
     */
    protected function parseIds(string $idsString): array
    {
        if (!$idsString) {
            return [];
        }

        $ids = explode(',', trim($idsString));
        $ids = array_filter($ids, function ($id) {
            return (bool)$id && is_numeric($id);
        });

        return array_map('intval', array_unique($ids));
    }

    /**
     * @param array<int> $assetIds
     * @param \Generated\Shared\Transfer\SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer
     * @param bool $isAssignment
     *
     * @return void
     */
    protected function addAssetAssignments(array $assetIds, SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer, bool $isAssignment): void
    {
        foreach ($assetIds as $assetId) {
            $modelSspAssetAssignmentTransfer = new ModelSspAssetAssignmentTransfer();
            $sspAssetTransfer = new SspAssetTransfer();
            $sspAssetTransfer->setIdSspAsset($assetId);

            $modelSspAssetAssignmentTransfer
                ->setSspAsset($sspAssetTransfer)
                ->setSspModel($this->sspModelTransfer);

            if ($isAssignment) {
                $sspModelCollectionRequestTransfer->addSspAssetToBeAssigned($modelSspAssetAssignmentTransfer);

                continue;
            }

            $sspModelCollectionRequestTransfer->addSspAssetToBeUnassigned($modelSspAssetAssignmentTransfer);
        }
    }

    /**
     * @param array<int> $productListIds
     * @param \Generated\Shared\Transfer\SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer
     * @param bool $isAssignment
     *
     * @return void
     */
    protected function addProductListAssignments(
        array $productListIds,
        SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer,
        bool $isAssignment
    ): void {
        foreach ($productListIds as $productListId) {
            $modelProductListAssignmentTransfer = new ModelProductListAssignmentTransfer();
            $productListTransfer = new ProductListTransfer();
            $productListTransfer->setIdProductList($productListId);

            $modelProductListAssignmentTransfer
                ->setProductList($productListTransfer)
                ->setSspModel($this->sspModelTransfer);

            if ($isAssignment) {
                $sspModelCollectionRequestTransfer->addProductListToBeAssigned($modelProductListAssignmentTransfer);

                continue;
            }

            $sspModelCollectionRequestTransfer->addProductListToBeUnassigned($modelProductListAssignmentTransfer);
        }
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\ModelSspAssetAssignmentTransfer> $modelSspAssetAssignmentTransfers
     *
     * @return string
     */
    protected function extractAssetIds(ArrayObject $modelSspAssetAssignmentTransfers): string
    {
        $ids = [];
        foreach ($modelSspAssetAssignmentTransfers as $modelSspAssetAssignmentTransfer) {
            $ids[] = $modelSspAssetAssignmentTransfer->getSspAssetOrFail()->getIdSspAsset();
        }

        return implode(',', $ids);
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\ModelProductListAssignmentTransfer> $modelProductListAssignmentTransfers
     *
     * @return string
     */
    protected function extractProductListIds(ArrayObject $modelProductListAssignmentTransfers): string
    {
        $ids = [];
        foreach ($modelProductListAssignmentTransfers as $modelProductListAssignmentTransfer) {
            $ids[] = $modelProductListAssignmentTransfer->getProductListOrFail()->getIdProductList();
        }

        return implode(',', $ids);
    }
}
