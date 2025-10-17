<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form\DataTransformer;

use ArrayObject;
use Generated\Shared\Transfer\ModelProductListAttachmentTransfer;
use Generated\Shared\Transfer\ModelSspAssetAttachmentTransfer;
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
    protected const FORM_FIELD_SSP_ASSET_IDS_TO_BE_ATTACHED = 'sspAssetIdsToBeAttached';

    /**
     * @var string
     */
    protected const FORM_FIELD_SSP_ASSET_IDS_TO_BE_UNATTACHED = 'sspAssetIdsToBeUnattached';

    /**
     * @var string
     */
    protected const FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_ATTACHED = 'productListIdsToBeAttached';

    /**
     * @var string
     */
    protected const FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_UNATTACHED = 'productListIdsToBeUnttached';

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
            static::FORM_FIELD_SSP_ASSET_IDS_TO_BE_ATTACHED => $this->extractAssetIds($value->getSspAssetsToBeAttached()),
            static::FORM_FIELD_SSP_ASSET_IDS_TO_BE_UNATTACHED => $this->extractAssetIds($value->getSspAssetsToBeUnattached()),
            static::FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_ATTACHED => $this->extractProductListIds($value->getProductListsToBeAttached()),
            static::FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_UNATTACHED => $this->extractProductListIds($value->getProductListsToBeUnattached()),
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

        $this->processAssetAttachments($value, $sspModelCollectionRequestTransfer);
        $this->processProductListAttachments($value, $sspModelCollectionRequestTransfer);

        return $sspModelCollectionRequestTransfer;
    }

    /**
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer
     *
     * @return void
     */
    protected function processAssetAttachments(array $formData, SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer): void
    {
        $assetsToAttach = $this->parseIds($formData[static::FORM_FIELD_SSP_ASSET_IDS_TO_BE_ATTACHED] ?? '');
        $assetsToUnattach = $this->parseIds($formData[static::FORM_FIELD_SSP_ASSET_IDS_TO_BE_UNATTACHED] ?? '');

        $this->addAssetAttachments($assetsToAttach, $sspModelCollectionRequestTransfer, true);
        $this->addAssetAttachments($assetsToUnattach, $sspModelCollectionRequestTransfer, false);
    }

    /**
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer
     *
     * @return void
     */
    protected function processProductListAttachments(array $formData, SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer): void
    {
        $productListsToAttach = $this->parseIds($formData[static::FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_ATTACHED] ?? '');
        $productListsToUnattach = $this->parseIds($formData[static::FORM_FIELD_PRODUCT_LIST_IDS_TO_BE_UNATTACHED] ?? '');

        $this->addProductListAttachments($productListsToAttach, $sspModelCollectionRequestTransfer, true);
        $this->addProductListAttachments($productListsToUnattach, $sspModelCollectionRequestTransfer, false);
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
     * @param bool $isAttachment
     *
     * @return void
     */
    protected function addAssetAttachments(array $assetIds, SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer, bool $isAttachment): void
    {
        foreach ($assetIds as $assetId) {
            $modelSspAssetAttachmentTransfer = new ModelSspAssetAttachmentTransfer();
            $sspAssetTransfer = new SspAssetTransfer();
            $sspAssetTransfer->setIdSspAsset($assetId);

            $modelSspAssetAttachmentTransfer
                ->setSspAsset($sspAssetTransfer)
                ->setSspModel($this->sspModelTransfer);

            if ($isAttachment) {
                $sspModelCollectionRequestTransfer->addSspAssetToBeAttached($modelSspAssetAttachmentTransfer);

                continue;
            }

            $sspModelCollectionRequestTransfer->addSspAssetToBeUnattached($modelSspAssetAttachmentTransfer);
        }
    }

    /**
     * @param array<int> $productListIds
     * @param \Generated\Shared\Transfer\SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer
     * @param bool $isAttachment
     *
     * @return void
     */
    protected function addProductListAttachments(
        array $productListIds,
        SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer,
        bool $isAttachment
    ): void {
        foreach ($productListIds as $productListId) {
            $modelProductListAttachmentTransfer = new ModelProductListAttachmentTransfer();
            $productListTransfer = new ProductListTransfer();
            $productListTransfer->setIdProductList($productListId);

            $modelProductListAttachmentTransfer
                ->setProductList($productListTransfer)
                ->setSspModel($this->sspModelTransfer);

            if ($isAttachment) {
                $sspModelCollectionRequestTransfer->addProductListToBeAttached($modelProductListAttachmentTransfer);

                continue;
            }

            $sspModelCollectionRequestTransfer->addProductListToBeUnattached($modelProductListAttachmentTransfer);
        }
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ModelSspAssetAttachmentTransfer> $modelSspAssetAttachmentTransfers
     *
     * @return string
     */
    protected function extractAssetIds(ArrayObject $modelSspAssetAttachmentTransfers): string
    {
        $ids = [];
        foreach ($modelSspAssetAttachmentTransfers as $modelSspAssetAttachmentTransfer) {
            $ids[] = $modelSspAssetAttachmentTransfer->getSspAssetOrFail()->getIdSspAsset();
        }

        return implode(',', $ids);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ModelProductListAttachmentTransfer> $modelProductListAttachmentTransfers
     *
     * @return string
     */
    protected function extractProductListIds(ArrayObject $modelProductListAttachmentTransfers): string
    {
        $ids = [];
        foreach ($modelProductListAttachmentTransfers as $modelProductListAttachmentTransfer) {
            $ids[] = $modelProductListAttachmentTransfer->getProductListOrFail()->getIdProductList();
        }

        return implode(',', $ids);
    }
}
