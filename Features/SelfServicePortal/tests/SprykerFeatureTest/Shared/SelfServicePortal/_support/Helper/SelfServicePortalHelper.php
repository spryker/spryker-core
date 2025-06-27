<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Shared\SelfServicePortal\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\CmsBlockBuilder;
use Generated\Shared\DataBuilder\CmsBlockGlossaryPlaceholderBuilder;
use Generated\Shared\DataBuilder\CmsBlockGlossaryPlaceholderTranslationBuilder;
use Generated\Shared\DataBuilder\FileAttachmentBuilder;
use Generated\Shared\DataBuilder\ProductAbstractTypeBuilder;
use Generated\Shared\DataBuilder\SspAssetBuilder;
use Generated\Shared\DataBuilder\SspInquiryBuilder;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\ProductAbstractTypeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use InvalidArgumentException;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractToProductAbstractTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentType;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnit;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryFile;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySalesOrder;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAssetQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Ramsey\Uuid\Nonstandard\Uuid;
use Spryker\Service\UtilDateTime\UtilDateTimeService;
use Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface;
use Spryker\Zed\FileManager\Business\FileManagerFacade;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspAssetMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspInquiryMapper;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig as ZedSelfServicePortalConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class SelfServicePortalHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @return void
     */
    public function ensureProductShipmentTypeTableIsEmpty(): void
    {
        $this->createProductShipmentTypeQuery()->deleteAll();
    }

    /**
     * @param array<string, mixed> $productAbstractTypeOverride
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTypeTransfer
     */
    public function haveProductAbstractType(array $productAbstractTypeOverride = []): ProductAbstractTypeTransfer
    {
        $productAbstractTypeTransfer = (new ProductAbstractTypeBuilder($productAbstractTypeOverride))
            ->build();

        $productAbstractTypeEntity = $this->getProductAbstractTypeQuery()
            ->filterByKey($productAbstractTypeTransfer->getKey())
            ->findOneOrCreate();

        $productAbstractTypeEntity->fromArray($productAbstractTypeTransfer->modifiedToArray());
        if ($productAbstractTypeEntity->isNew() || $productAbstractTypeEntity->isModified()) {
            $productAbstractTypeEntity->save();
        }

        $productAbstractTypeTransfer->setIdProductAbstractType($productAbstractTypeEntity->getIdProductAbstractType());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productAbstractTypeTransfer): void {
            $this->cleanupProductAbstractType($productAbstractTypeTransfer->getIdProductAbstractType());
        });

        return $productAbstractTypeTransfer;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProductAbstractType
     *
     * @return void
     */
    public function haveProductAbstractToProductAbstractType(
        int $idProductAbstract,
        int $idProductAbstractType
    ): void {
        $productAbstractToProductAbstractTypeEntity = $this->getProductAbstractToProductAbstractTypeQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkProductAbstractType($idProductAbstractType)
            ->findOneOrCreate();

        if ($productAbstractToProductAbstractTypeEntity->isNew() || $productAbstractToProductAbstractTypeEntity->isModified()) {
            $productAbstractToProductAbstractTypeEntity->save();
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($idProductAbstract, $idProductAbstractType): void {
            $this->cleanupProductAbstractToProductAbstractType(
                $idProductAbstract,
                $idProductAbstractType,
            );
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return void
     */
    public function haveProductConcreteShipmentType(
        ProductConcreteTransfer $productConcreteTransfer,
        ShipmentTypeTransfer $shipmentTypeTransfer
    ): void {
        $productShipmentTypeEntity = $this->createProductShipmentTypeQuery()
            ->filterByFkProduct($productConcreteTransfer->getIdProductConcreteOrFail())
            ->filterByFkShipmentType($shipmentTypeTransfer->getIdShipmentTypeOrFail())
            ->findOneOrCreate();

        $productShipmentTypeEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productShipmentTypeEntity): void {
            $this->deleteProductConcreteShipmentType($productShipmentTypeEntity);
        });
    }

    /**
     * @param array $seedData
     * @param int|null $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function haveSalesRepresentativeCmsBlockForBusinessUnit(array $seedData = [], ?int $idCompanyBusinessUnit = 0): CmsBlockGlossaryTransfer
    {
        $cmsBlockTemplateTransfer = $this->getCmsBlockFacade()->findTemplate('@CmsBlock/template/title_and_content_block.twig');

        $cmsBlockTransfer = (new CmsBlockBuilder($seedData))->build();
        $this->setStoreRelation($cmsBlockTransfer, $seedData);
        $blockName = $cmsBlockTransfer->getName() . $idCompanyBusinessUnit;
        $cmsBlockTransfer->setName($blockName)
            ->setKey($blockName)
            ->setIdCmsBlock(null)
            ->setFkTemplate($cmsBlockTemplateTransfer->getIdCmsBlockTemplate())
            ->setTemplateName($cmsBlockTemplateTransfer->getTemplateName());

        $cmsBlockTransfer = $this->getCmsBlockFacade()->createCmsBlock($cmsBlockTransfer);

        $this->createTranslations($cmsBlockTransfer);

        return $this->getCmsBlockFacade()->findGlossary($cmsBlockTransfer->getIdCmsBlockOrFail());
    }

    /**
     * @param array<string, mixed> $seedData
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\FileAttachmentTransfer
     */
    public function haveFileAttachment(array $seedData): FileAttachmentTransfer
    {
        $fileAttachmentTransfer = (new FileAttachmentBuilder($seedData))->build();

        $entityName = $fileAttachmentTransfer->getEntityNameOrFail();
        $entityId = $fileAttachmentTransfer->getEntityIdOrFail();
        $idFile = $fileAttachmentTransfer->getFileOrFail()->getIdFileOrFail();

        match ($entityName) {
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY => $this->createCompanyFileAttachment($idFile, $entityId),
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER => $this->createCompanyUserFileAttachment($idFile, $entityId),
            SelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => $this->createCompanyBusinessUnitFileAttachment($idFile, $entityId),
            SelfServicePortalConfig::ENTITY_TYPE_SSP_ASSET => $this->createSspAssetFileAttachment($idFile, $entityId),
            default => throw new InvalidArgumentException("Invalid entity type: $entityName"),
        };

        return $fileAttachmentTransfer;
    }

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function haveSspInquiry(array $seedData = []): SspInquiryTransfer
    {
        $sspInquiryTransfer = (new SspInquiryBuilder($seedData))->build();

        if (!$sspInquiryTransfer->getStore()->getIdStore()) {
            $sspInquiryTransfer->getStore()->setIdStore(
                SpyStoreQuery::create()->findOneByName($sspInquiryTransfer->getStore()->getName())->getIdStore(),
            );
        }
        $sspInquiryEntity = (new SspInquiryMapper())->mapSspInquiryTransferToSspInquiryEntity($sspInquiryTransfer, new SpySspInquiry());

        if ($sspInquiryTransfer->getStatus()) {
            $stateMachineItemState = SpyStateMachineItemStateQuery::create()->findOneByName($sspInquiryTransfer->getStatus());
            if ($stateMachineItemState) {
                $sspInquiryEntity->setFkStateMachineItemState($stateMachineItemState->getIdStateMachineItemState());
            }
        }

        if ($sspInquiryTransfer->getCreatedDate()) {
            $sspInquiryEntity->setCreatedAt($sspInquiryTransfer->getCreatedDate());
        }

        $sspInquiryEntity->save();
        $sspInquiryTransfer->setIdSspInquiry($sspInquiryEntity->getIdSspInquiry());
        if ($sspInquiryTransfer->getOrder()) {
            (new SpySspInquirySalesOrder())
                ->setFkSspInquiry($sspInquiryTransfer->getIdSspInquiry())
                ->setFkSalesOrder($sspInquiryTransfer->getOrder()->getIdSalesOrder())
                ->save();
        }

        if ($sspInquiryTransfer->getSspAsset()) {
            (new SpySspInquirySspAsset())
                ->setFkSspInquiry($sspInquiryTransfer->getIdSspInquiry())
                ->setFkSspAsset($sspInquiryTransfer->getSspAsset()->getIdSspAsset());
        }

        $this->generateAndSaveSspInquiryImages($seedData['fileAmount'] ?? 0, $sspInquiryTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($sspInquiryTransfer): void {
            $this->debug(sprintf('Deleting Ssp Inquiry: %s', $sspInquiryTransfer->getIdSspInquiry()));
            SpySspInquiryFileQuery::create()->filterByFkSspInquiry($sspInquiryTransfer->getIdSspInquiry())->delete();
            SpySspInquiryQuery::create()->filterByIdSspInquiry($sspInquiryTransfer->getIdSspInquiry())->delete();
        });

        return $sspInquiryTransfer;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSspAsset
     *
     * @return void
     */
    public function haveSalesSspAsset(int $idSalesOrder, int $idSspAsset): void
    {
        $salesOrderEntity = $this->getSalesOrderQuery()
            ->filterByIdSalesOrder($idSalesOrder)
            ->findOne();

        $sspAssetEntity = $this->getSspAssetQuery()
            ->filterByIdSspAsset($idSspAsset)
            ->findOne();

        if ($salesOrderEntity === null || $sspAssetEntity === null) {
            return;
        }

        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            (new SpySalesOrderItemSspAsset())
                ->setName($sspAssetEntity->getName())
                ->setReference($sspAssetEntity->getReference())
                ->setSerialNumber($sspAssetEntity->getSerialNumber())
                ->setFkSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem())
                ->save();
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($idSalesOrder, $idSspAsset): void {
            $this->cleanupSalesSspAsset(
                $idSalesOrder,
                $idSspAsset,
            );
        });
    }

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function haveAsset(array $seedData = []): SspAssetTransfer
    {
        $sspAssetTransfer = (new SspAssetBuilder($seedData))->build();

        $sspAssetEntity = (new SspAssetMapper(new UtilDateTimeService()))->mapSspAssetTransferToSpySspAssetEntity($sspAssetTransfer, new SpySspAsset());

        if (isset($seedData['image'])) {
            $this->attachImageToAsset($sspAssetTransfer, $seedData['image']);
        } elseif (isset($seedData['generateImage']) && $seedData['generateImage']) {
            $this->attachImageToAsset($sspAssetTransfer, $this->generateSmallFile());
        }

        if ($sspAssetTransfer->getImage()) {
            $sspAssetEntity->setFkImageFile($sspAssetTransfer->getImageOrFail()->getIdFileOrFail());
        }

        $sspAssetEntity->save();
        foreach ($sspAssetTransfer->getBusinessUnitAssignments() as $assignment) {
            (new SpySspAssetToCompanyBusinessUnit())
                ->setFkSspAsset($sspAssetEntity->getIdSspAsset())
                ->setFkCompanyBusinessUnit($assignment->getCompanyBusinessUnit()->getIdCompanyBusinessUnit())
                ->save();
        }

        if (isset($seedData['sspInquiries'])) {
            foreach ($seedData['sspInquiries'] as $sspInquiry) {
                (new SpySspInquirySspAsset())
                    ->setFkSspInquiry($sspInquiry->getIdSspInquiry())
                    ->setFkSspAsset($sspAssetEntity->getIdSspAsset())
                    ->save();
            }
        }

        $sspAssetTransfer->setIdSspAsset($sspAssetEntity->getIdSspAsset());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($sspAssetTransfer): void {
            $this->debug(sprintf('Deleting Asset: %s', $sspAssetTransfer->getIdSspAsset()));
            SpySspAssetToCompanyBusinessUnitQuery::create()->filterByFkSspAsset($sspAssetTransfer->getIdSspAsset())->delete();
            SpySspInquirySspAssetQuery::create()->filterByFkSspAsset($sspAssetTransfer->getIdSspAsset())->delete();
            SpySspAssetQuery::create()->filterByIdSspAsset($sspAssetTransfer->getIdSspAsset())->delete();
        });

        return $sspAssetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param array<string, mixed> $imageData
     *
     * @return void
     */
    public function attachImageToAsset(SspAssetTransfer $sspAssetTransfer, array $imageData): void
    {
        $fileManagerDataTransfer = new FileManagerDataTransfer();
        $fileName = sprintf('%s.%s', Uuid::uuid4()->toString(), $imageData['extension']);

        $fileManagerDataTransfer->setFileInfo(
            (new FileInfoTransfer())
                ->setStorageFileName($fileName)
                ->setStorageName((new ZedSelfServicePortalConfig())->getAssetStorageName())
                ->setExtension($imageData['extension'])
                ->setSize(strlen($imageData['content']))
                ->setType($imageData['type']),
        );
        $fileManagerDataTransfer->setContent($imageData['content']);
        $fileManagerDataTransfer->setFile(
            (new FileTransfer())
                ->setFileName($fileName)
                ->setEncodedContent(base64_encode(gzencode($imageData['content'])))
                ->setFileUpload(
                    (new FileUploadTransfer())
                        ->setSize(strlen($imageData['content']))
                        ->setMimeTypeName($imageData['type'])
                        ->setClientOriginalExtension($imageData['extension']),
                ),
        );

        $fileManagerDataTransfer = (new FileManagerFacade())->saveFile($fileManagerDataTransfer);

        $sspAssetTransfer->setImage($fileManagerDataTransfer->getFileOrFail());
    }

    /**
     * Generates a small image.
     *
     * @return array<string, string>
     */
    public function generateSmallFile(): array
    {
        $extensions = ['png', 'jpg', 'jpeg', 'heic'];
        $extension = $extensions[array_rand($extensions)];
        $size = rand(1, 200 * 1024); // Random size from 1B to 200kB

        $imageContent = '';
        $type = '';
        switch ($extension) {
            case 'png':
            case 'jpg':
            case 'jpeg':
                $image = imagecreatetruecolor(100, 100);
                $backgroundColor = imagecolorallocate($image, 255, 0, 0);
                imagefill($image, 0, 0, $backgroundColor);

                ob_start();
                if ($extension === 'png') {
                    imagepng($image);
                    $type = 'image/png';
                } else {
                    imagejpeg($image);
                    $type = 'image/jpeg';
                }
                $imageContent = ob_get_clean();
                imagedestroy($image);

                break;
            case 'heic':
                // Simulate a HEIC file with random content
                $imageContent = str_repeat('H', $size);
                $type = 'image/heic';

                break;
        }

        if (strlen($imageContent) > $size) {
            $imageContent = substr($imageContent, 0, $size);
        } else {
            $imageContent = str_pad($imageContent, $size, ' ');
        }

        return ['content' => $imageContent, 'extension' => $extension, 'type' => $type];
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSspAsset
     *
     * @return void
     */
    protected function cleanupSalesSspAsset(int $idSalesOrder, int $idSspAsset): void
    {
        $salesOrderEntity = $this->getSalesOrderQuery()
            ->filterByIdSalesOrder($idSalesOrder)
            ->findOne();

        if ($salesOrderEntity === null) {
            return;
        }

        $sspAssetEntity = $this->getSspAssetQuery()
            ->filterByIdSspAsset($idSspAsset)
            ->findOne();

        if ($sspAssetEntity === null) {
            return;
        }

        $salesOrderItemIds = [];
        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            $salesOrderItemIds[] = $salesOrderItemEntity->getIdSalesOrderItem();
        }

        if ($salesOrderItemIds) {
            SpySalesOrderItemSspAssetQuery::create()
                ->filterByFkSalesOrderItem_In($salesOrderItemIds)
                ->filterByReference($sspAssetEntity->getReference())
                ->delete();
        }
    }

    /**
     * Generates and saves small images for the ssp inquiry.
     *
     * @param int $fileAmount
     * @param \Generated\Shared\Transfer\SspInquiryTransfer|int $sspInquiryTransfer
     *
     * @return void
     */
    protected function generateAndSaveSspInquiryImages(int $fileAmount, SspInquiryTransfer $sspInquiryTransfer): void
    {
        for ($i = 0; $i < $fileAmount; $i++) {
            $file = $this->generateSmallFile();
            $fileName = sprintf('%s.%s', Uuid::uuid4()->toString(), $file['extension']);
            $fileManagerDataTransfer = new FileManagerDataTransfer();
            $fileManagerDataTransfer->setFileInfo(
                (new FileInfoTransfer())
                    ->setStorageFileName($fileName)
                    ->setStorageName((new SelfServicePortalConfig())->getInquiryStorageName())
                    ->setExtension($file['extension'])
                    ->setSize(strlen($file['content']))
                    ->setType($file['type']),
            );
            $fileManagerDataTransfer->setContent($file['content']);
            $fileManagerDataTransfer->setFile(
                (new FileTransfer())->setFileName($fileName),
            );

            $fileManagerDataTransfer = (new FileManagerFacade())->saveFile($fileManagerDataTransfer);
            (new SpySspInquiryFile())->setFkSspInquiry($sspInquiryTransfer->getIdSspInquiry())
                ->setFkFile($fileManagerDataTransfer->getFile()->getIdFile())
                ->save();
            $sspInquiryTransfer->addFile(
                (new FileTransfer())
                    ->setIdFile($fileManagerDataTransfer->getFile()->getIdFile())
                    ->setFileName($fileName)
                    ->setFileInfo(new ArrayObject([$fileManagerDataTransfer->getFileInfo()])),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $seedData
     *
     * @return void
     */
    protected function setStoreRelation(CmsBlockTransfer $cmsBlockTransfer, array $seedData = []): void
    {
        if (!isset($seedData[CmsBlockTransfer::STORE_RELATION])) {
            return;
        }

        $cmsBlockTransfer->setStoreRelation(
            (new StoreRelationTransfer())
                ->fromArray($seedData[CmsBlockTransfer::STORE_RELATION]),
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface
     */
    protected function getCmsBlockFacade(): CmsBlockFacadeInterface
    {
        return $this->getLocator()->cmsBlock()->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function createTranslations(CmsBlockTransfer $cmsBlockTransfer): void
    {
        $cmsBlockGlossaryPlaceholderTranslationTransfer = (new CmsBlockGlossaryPlaceholderTranslationBuilder())
            ->build()
            ->setFkLocale($cmsBlockTransfer->getLocale()->getIdLocale())
            ->setLocaleName($cmsBlockTransfer->getLocale()->getLocaleName());

        $contentCmsBlockGlossaryPlaceholderTransfer = (new CmsBlockGlossaryPlaceholderBuilder())
            ->build()
            ->setPlaceholder('content')
            ->addTranslation($cmsBlockGlossaryPlaceholderTranslationTransfer)
            ->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->setTemplateName($cmsBlockTransfer->getTemplateName());
        $descriptionCmsBlockGlossaryPlaceholderTransfer = (new CmsBlockGlossaryPlaceholderBuilder())
            ->build()
            ->setPlaceholder('description')
            ->addTranslation($cmsBlockGlossaryPlaceholderTranslationTransfer)
            ->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->setTemplateName($cmsBlockTransfer->getTemplateName());
        $titleCmsBlockGlossaryPlaceholderTransfer = (new CmsBlockGlossaryPlaceholderBuilder())
            ->build()
            ->setPlaceholder('title')
            ->addTranslation($cmsBlockGlossaryPlaceholderTranslationTransfer)
            ->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->setTemplateName($cmsBlockTransfer->getTemplateName());

        $cmsBlockGlossaryTransfer = (new CmsBlockGlossaryTransfer())
            ->addGlossaryPlaceholder($contentCmsBlockGlossaryPlaceholderTransfer)
            ->addGlossaryPlaceholder($descriptionCmsBlockGlossaryPlaceholderTransfer)
            ->addGlossaryPlaceholder($titleCmsBlockGlossaryPlaceholderTransfer);

        $this->getCmsBlockFacade()->saveGlossary($cmsBlockGlossaryTransfer);
    }

    /**
     * @param int $idProductAbstractType
     *
     * @return void
     */
    protected function cleanupProductAbstractType(int $idProductAbstractType): void
    {
        $this->getProductAbstractToProductAbstractTypeQuery()
            ->filterByFkProductAbstractType($idProductAbstractType)
            ->delete();

        $this->getProductAbstractTypeQuery()
            ->filterByIdProductAbstractType($idProductAbstractType)
            ->delete();
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProductAbstractType
     *
     * @return void
     */
    protected function cleanupProductAbstractToProductAbstractType(int $idProductAbstract, int $idProductAbstractType): void
    {
        $this->getProductAbstractToProductAbstractTypeQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkProductAbstractType($idProductAbstractType)
            ->delete();
    }

    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentType $productShipmentTypeEntity
     *
     * @return void
     */
    protected function deleteProductConcreteShipmentType(SpyProductShipmentType $productShipmentTypeEntity): void
    {
        $this->createProductShipmentTypeQuery()
            ->filterByIdProductShipmentType($productShipmentTypeEntity->getIdProductShipmentType())
            ->delete();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery
     */
    protected function createProductShipmentTypeQuery(): SpyProductShipmentTypeQuery
    {
        return SpyProductShipmentTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractTypeQuery
     */
    protected function getProductAbstractTypeQuery(): SpyProductAbstractTypeQuery
    {
        return SpyProductAbstractTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractToProductAbstractTypeQuery
     */
    protected function getProductAbstractToProductAbstractTypeQuery(): SpyProductAbstractToProductAbstractTypeQuery
    {
        return SpyProductAbstractToProductAbstractTypeQuery::create();
    }

    /**
     * @return void
     */
    public function ensureFileAttachmentTablesAreEmpty(): void
    {
        $this->createCompanyFileQuery()->deleteAll();
        $this->createCompanyUserFileQuery()->deleteAll();
        $this->createCompanyBusinessUnitFileQuery()->deleteAll();
    }

    /**
     * @param int $idFile
     * @param int $idCompany
     *
     * @return void
     */
    protected function createCompanyFileAttachment(int $idFile, int $idCompany): void
    {
        $companyFileEntity = $this->createCompanyFileQuery()
            ->filterByFkFile($idFile)
            ->filterByFkCompany($idCompany)
            ->findOneOrCreate();

        $companyFileEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($companyFileEntity): void {
            $companyFileEntity->delete();
        });
    }

    /**
     * @param int $idFile
     * @param int $idCompanyUser
     *
     * @return void
     */
    protected function createCompanyUserFileAttachment(int $idFile, int $idCompanyUser): void
    {
        $companyUserFileEntity = $this->createCompanyUserFileQuery()
            ->filterByFkFile($idFile)
            ->filterByFkCompanyUser($idCompanyUser)
            ->findOneOrCreate();

        $companyUserFileEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($companyUserFileEntity): void {
            $companyUserFileEntity->delete();
        });
    }

    /**
     * @param int $idFile
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    protected function createCompanyBusinessUnitFileAttachment(
        int $idFile,
        int $idCompanyBusinessUnit
    ): void {
        $companyBusinessUnitFileEntity = $this->createCompanyBusinessUnitFileQuery()
            ->filterByFkFile($idFile)
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->findOneOrCreate();

        $companyBusinessUnitFileEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($companyBusinessUnitFileEntity): void {
            $companyBusinessUnitFileEntity->delete();
        });
    }

    /**
     * @param int $idFile
     * @param int $idSspAsset
     *
     * @return void
     */
    protected function createSspAssetFileAttachment(
        int $idFile,
        int $idSspAsset
    ): void {
        $sspAssetFileEntity = $this->createSspAssetFileQuery()
            ->filterByFkFile($idFile)
            ->filterByFkSspAsset($idSspAsset)
            ->findOneOrCreate();

        $sspAssetFileEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($sspAssetFileEntity): void {
            $sspAssetFileEntity->delete();
        });
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery
     */
    public function createCompanyFileQuery(): SpyCompanyFileQuery
    {
        return SpyCompanyFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery
     */
    public function createCompanyUserFileQuery(): SpyCompanyUserFileQuery
    {
        return SpyCompanyUserFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery
     */
    public function createCompanyBusinessUnitFileQuery(): SpyCompanyBusinessUnitFileQuery
    {
        return SpyCompanyBusinessUnitFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery
     */
    public function createSspAssetFileQuery(): SpySspAssetFileQuery
    {
        return SpySspAssetFileQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return SpySalesOrderQuery::create();
    }

    /**
     * @return \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery
     */
    protected function getSspAssetQuery(): SpySspAssetQuery
    {
        return SpySspAssetQuery::create();
    }
}
