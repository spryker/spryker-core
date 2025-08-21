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
use Generated\Shared\DataBuilder\ProductClassBuilder;
use Generated\Shared\DataBuilder\SalesProductClassBuilder;
use Generated\Shared\DataBuilder\SspAssetBuilder;
use Generated\Shared\DataBuilder\SspInquiryBuilder;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductClassTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SalesProductClassTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\SelfServicePortal\Persistence\Base\SpySspAssetSearchQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentType;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetSearch;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnit;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToSspModel;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToSspModelQuery;
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
     * @param array<string, mixed> $productClassOverride
     *
     * @return \Generated\Shared\Transfer\ProductClassTransfer
     */
    public function haveProductClass(array $productClassOverride = []): ProductClassTransfer
    {
        $productClassTransfer = (new ProductClassBuilder($productClassOverride))->build();

        $productClassEntity = $this->getProductClassQuery()
            ->filterByKey($productClassTransfer->getKey())
            ->findOneOrCreate();

        $productClassEntity->fromArray($productClassTransfer->modifiedToArray());
        if ($productClassEntity->isNew() || $productClassEntity->isModified()) {
            $productClassEntity->save();
        }

        $productClassTransfer->setIdProductClass($productClassEntity->getIdProductClass());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productClassTransfer): void {
            $this->cleanupProductClass($productClassTransfer->getIdProductClass());
        });

        return $productClassTransfer;
    }

    public function haveProductToProductClass(
        int $idProduct,
        int $idProductClass
    ): void {
        $productToProductClassEntity = $this->getProductToProductClassQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductClass($idProductClass)
            ->findOneOrCreate();

        if ($productToProductClassEntity->isNew() || $productToProductClassEntity->isModified()) {
            $productToProductClassEntity->save();
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($idProduct, $idProductClass): void {
            $this->cleanupProductToProductClass($idProduct, $idProductClass);
        });
    }

    public function haveSalesOrderItemToProductClass(
        int $idSalesOrderItem,
        int $idSalesProductClass
    ): void {
        $salesOrderItemToProductClassEntity = $this->getSalesOrderItemProductClassQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->filterByFkSalesProductClass($idSalesProductClass)
            ->findOneOrCreate();

        if ($salesOrderItemToProductClassEntity->isNew() || $salesOrderItemToProductClassEntity->isModified()) {
            $salesOrderItemToProductClassEntity->save();
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($idSalesOrderItem, $idSalesProductClass): void {
            $this->cleanupSalesOrderItemToProductClass($idSalesOrderItem, $idSalesProductClass);
        });
    }

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
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function haveSspInquiry(array $seedData = []): SspInquiryTransfer
    {
        $sspInquiryTransfer = (new SspInquiryBuilder($seedData))->build();

        if (!$sspInquiryTransfer->getStore()->getIdStore()) {
            $sspInquiryTransfer->getStore()->setIdStore(
                $this->getStoreQuery()->findOneByName($sspInquiryTransfer->getStore()->getName())->getIdStore(),
            );
        }
        $sspInquiryEntity = (new SspInquiryMapper())->mapSspInquiryTransferToSspInquiryEntity($sspInquiryTransfer, new SpySspInquiry());

        if ($sspInquiryTransfer->getStatus()) {
            $stateMachineItemState = $this->getStateMachineItemStateQuery()->findOneByName($sspInquiryTransfer->getStatus());
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
            $this->getSspInquiryFileQuery()->filterByFkSspInquiry($sspInquiryTransfer->getIdSspInquiry())->delete();
            $this->getSspInquiryQuery()->filterByIdSspInquiry($sspInquiryTransfer->getIdSspInquiry())->delete();
        });

        return $sspInquiryTransfer;
    }

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

        foreach ($sspAssetTransfer->getSspModels() as $sspModelTransfer) {
            (new SpySspAssetToSspModel())
                ->setFkSspAsset($sspAssetEntity->getIdSspAsset())
                ->setFkSspModel($sspModelTransfer->getIdSspModel())
                ->save();
        }

        $sspAssetTransfer->setIdSspAsset($sspAssetEntity->getIdSspAsset());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($sspAssetTransfer): void {
            $this->debug(sprintf('Deleting Asset: %s', $sspAssetTransfer->getIdSspAsset()));
            $this->getSspAssetToCompanyBusinessUnitQuery()->filterByFkSspAsset($sspAssetTransfer->getIdSspAsset())->delete();
            $this->getSspInquirySspAssetQuery()->filterByFkSspAsset($sspAssetTransfer->getIdSspAsset())->delete();
            $this->getSspAssetQuery()->filterByIdSspAsset($sspAssetTransfer->getIdSspAsset())->delete();
            $this->getSspAssetToSspModelQuery()->filterByFkSspAsset($sspAssetTransfer->getIdSspAsset())->delete();
        });

        return $sspAssetTransfer;
    }

    public function haveSspAssetSearch(array $seedData = []): SpySspAssetSearch
    {
        $sspAssetSearchEntity = (new SpySspAssetSearch())->fromArray($seedData);

        $sspAssetSearchEntity->save();

        return $sspAssetSearchEntity;
    }

    /**
     * @param array<string, mixed> $salesProductClassOverride
     *
     * @return \Generated\Shared\Transfer\SalesProductClassTransfer
     */
    public function haveSalesProductClass(array $salesProductClassOverride = []): SalesProductClassTransfer
    {
        $salesProductClassTransfer = (new SalesProductClassBuilder($salesProductClassOverride))->build();

        $salesProductClassEntity = $this->getSalesProductClassQuery()
            ->filterByName($salesProductClassTransfer->getName())
            ->findOneOrCreate();

        $salesProductClassEntity->fromArray($salesProductClassTransfer->modifiedToArray());
        if ($salesProductClassEntity->isNew() || $salesProductClassEntity->isModified()) {
            $salesProductClassEntity->save();
        }

        $salesProductClassTransfer->setIdSalesProductClass($salesProductClassEntity->getIdSalesProductClass());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($salesProductClassTransfer): void {
            $this->getSalesProductClassQuery()->filterByIdSalesProductClass($salesProductClassTransfer->getIdSalesProductClass())->delete();
        });

        return $salesProductClassTransfer;
    }

    /**
     * @param array<string, int> $data
     *
     * @return void
     */
    public function haveCompanyFileAttachment(array $data): void
    {
        $companyFileEntity = $this->createCompanyFileQuery()
            ->filterByFkFile($data['idFile'])
            ->filterByFkCompany($data['idCompany'])
            ->findOneOrCreate();

        $companyFileEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($companyFileEntity): void {
            $companyFileEntity->delete();
        });
    }

    /**
     * @param array<string, int> $data
     *
     * @return void
     */
    public function haveCompanyUserFileAttachment(array $data): void
    {
        $companyUserFileEntity = $this->createCompanyUserFileQuery()
            ->filterByFkFile($data['idFile'])
            ->filterByFkCompanyUser($data['idCompanyUser'])
            ->findOneOrCreate();

        if (isset($data['attachedAt'])) {
            $companyUserFileEntity->setCreatedAt($data['attachedAt']);
        }

        $companyUserFileEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($companyUserFileEntity): void {
            $companyUserFileEntity->delete();
        });
    }

    public function haveCompanyBusinessUnitFileAttachment(array $data): void
    {
        $companyBusinessUnitFileEntity = $this->createCompanyBusinessUnitFileQuery()
            ->filterByFkFile($data['idFile'])
            ->filterByFkCompanyBusinessUnit($data['idCompanyBusinessUnit'])
            ->findOneOrCreate();

        $companyBusinessUnitFileEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($companyBusinessUnitFileEntity): void {
            $companyBusinessUnitFileEntity->delete();
        });
    }

    public function haveSspAssetFileAttachment(array $data): void
    {
        $sspAssetFileEntity = $this->createSspAssetFileQuery()
            ->filterByFkFile($data['idFile'])
            ->filterByFkSspAsset($data['idSspAsset'])
            ->findOneOrCreate();

        $sspAssetFileEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($sspAssetFileEntity): void {
            $sspAssetFileEntity->delete();
        });
    }

    public function haveCompanyBusinessUnitForDataImport(array $businessUnitData = []): void
    {
        $companyEntity = SpyCompanyQuery::create()
            ->filterByName('Test Company')
            ->findOneOrCreate();

        if ($companyEntity->isNew()) {
            $companyEntity->setName('Test Company')
                ->setKey('test_company')
                ->setStatus('approved')
                ->save();
        }

        $businessUnitEntity = SpyCompanyBusinessUnitQuery::create()
            ->filterByKey($businessUnitData['key'])
            ->findOneOrCreate();

        if ($businessUnitEntity->isNew()) {
            $businessUnitEntity->setFkCompany($companyEntity->getIdCompany())
                ->setKey($businessUnitData['key'])
                ->setName($businessUnitData['name'] ?? $businessUnitData['key'])
                ->save();
        }
    }

    public function ensureProductClassTableIsEmpty(): void
    {
        $this->getProductClassQuery()->deleteAll();
    }

    public function ensureFileAttachmentTablesAreEmpty(): void
    {
        $this->createCompanyFileQuery()->deleteAll();
        $this->createCompanyUserFileQuery()->deleteAll();
        $this->createCompanyBusinessUnitFileQuery()->deleteAll();
    }

    public function ensureSalesOrderItemProductClassDatabaseTablesAreEmpty(): void
    {
        $this->getSalesOrderItemProductClassQuery()->deleteAll();
        $this->getSalesProductClassQuery()->deleteAll();
    }

    public function ensureSspAssetTableIsEmpty(): void
    {
        $this->getSspAssetQuery()->deleteAll();
    }

    public function ensureSspAssetToCompanyBusinessUnitTableIsEmpty(): void
    {
        $this->getSspAssetToCompanyBusinessUnitQuery()->deleteAll();
    }

    public function ensureSspAssetToSspModelTableIsEmpty(): void
    {
        $this->getSspAssetToSspModelQuery()->deleteAll();
    }

    public function ensureSspAssetSearchTableIsEmpty(): void
    {
        $this->getSspAssetSearchQuery()->deleteAll();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array<\Generated\Shared\Transfer\ProductClassTransfer> $productClassTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function addProductClassesToProductAbstract(
        ProductAbstractTransfer $productAbstractTransfer,
        array $productClassTransfers
    ): ProductAbstractTransfer {
        foreach ($productClassTransfers as $productClassTransfer) {
            $this->haveProductToProductClass(
                $productAbstractTransfer->getIdProductAbstractOrFail(),
                $productClassTransfer->getIdProductClassOrFail(),
            );
            $productAbstractTransfer->addProductClass($productClassTransfer);
        }

        return $productAbstractTransfer;
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
                ->setStorageName((new ZedSelfServicePortalConfig())->getAssetImageFileUploadStorageName())
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
     * @return array<\Orm\Zed\SelfServicePortal\Persistence\SpySspAsset>
     */
    public function getAllSspAssets(): array
    {
        return $this->getSspAssetQuery()->find()->getData();
    }

    public function findSspAssetByReference(string $reference): ?SpySspAsset
    {
        return $this->getSspAssetQuery()->findOneByReference($reference);
    }

    /**
     * @return array<\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnit>
     */
    public function getAllSspAssetToCompanyBusinessUnitAssignments(): array
    {
        return $this->getSspAssetToCompanyBusinessUnitQuery()->find()->getData();
    }

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
            $this->getSalesOrderItemSspAssetQuery()
                ->filterByFkSalesOrderItem_In($salesOrderItemIds)
                ->filterByReference($sspAssetEntity->getReference())
                ->delete();
        }
    }

    /**
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
                    ->setStorageName((new ZedSelfServicePortalConfig())->getInquiryFileUploadStorageName())
                    ->setStorageFileName($fileName)
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

    protected function getCmsBlockFacade(): CmsBlockFacadeInterface
    {
        return $this->getLocator()->cmsBlock()->facade();
    }

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

    protected function cleanupProductClass(int $idProductClass): void
    {
        $this->getProductToProductClassQuery()
            ->filterByFkProductClass($idProductClass)
            ->delete();

        $this->getProductClassQuery()
            ->filterByIdProductClass($idProductClass)
            ->delete();
    }

    protected function cleanupProductToProductClass(int $idProduct, int $idProductClass): void
    {
        $this->getProductToProductClassQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductClass($idProductClass)
            ->delete();
    }

    protected function cleanupSalesOrderItemToProductClass(int $idSalesOrderItem, int $idSalesProductClass): void
    {
        $this->getSalesOrderItemProductClassQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->filterByFkSalesProductClass($idSalesProductClass)
            ->delete();
    }

    protected function deleteProductConcreteShipmentType(SpyProductShipmentType $productShipmentTypeEntity): void
    {
        $this->getProductShipmentTypeQuery()
            ->filterByIdProductShipmentType($productShipmentTypeEntity->getIdProductShipmentType())
            ->delete();
    }

    protected function createProductShipmentTypeQuery(): SpyProductShipmentTypeQuery
    {
        return SpyProductShipmentTypeQuery::create();
    }

    protected function getProductShipmentTypeQuery(): SpyProductShipmentTypeQuery
    {
        return SpyProductShipmentTypeQuery::create();
    }

    protected function getProductClassQuery(): SpyProductClassQuery
    {
        return SpyProductClassQuery::create();
    }

    protected function getProductToProductClassQuery(): SpyProductToProductClassQuery
    {
        return SpyProductToProductClassQuery::create();
    }

    protected function getSalesOrderItemProductClassQuery(): SpySalesOrderItemProductClassQuery
    {
        return SpySalesOrderItemProductClassQuery::create();
    }

    protected function getSalesProductClassQuery(): SpySalesProductClassQuery
    {
        return SpySalesProductClassQuery::create();
    }

    protected function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return SpySalesOrderQuery::create();
    }

    protected function getSspAssetQuery(): SpySspAssetQuery
    {
        return SpySspAssetQuery::create();
    }

    protected function getSspAssetToCompanyBusinessUnitQuery(): SpySspAssetToCompanyBusinessUnitQuery
    {
        return SpySspAssetToCompanyBusinessUnitQuery::create();
    }

    protected function getSspAssetToSspModelQuery(): SpySspAssetToSspModelQuery
    {
        return SpySspAssetToSspModelQuery::create();
    }

    protected function getStoreQuery(): SpyStoreQuery
    {
        return SpyStoreQuery::create();
    }

    protected function getStateMachineItemStateQuery(): SpyStateMachineItemStateQuery
    {
        return SpyStateMachineItemStateQuery::create();
    }

    protected function getSspInquiryQuery(): SpySspInquiryQuery
    {
        return SpySspInquiryQuery::create();
    }

    protected function getSspInquiryFileQuery(): SpySspInquiryFileQuery
    {
        return SpySspInquiryFileQuery::create();
    }

    protected function getSalesOrderItemSspAssetQuery(): SpySalesOrderItemSspAssetQuery
    {
        return SpySalesOrderItemSspAssetQuery::create();
    }

    public function createCompanyFileQuery(): SpyCompanyFileQuery
    {
        return SpyCompanyFileQuery::create();
    }

    public function createCompanyUserFileQuery(): SpyCompanyUserFileQuery
    {
        return SpyCompanyUserFileQuery::create();
    }

    public function createCompanyBusinessUnitFileQuery(): SpyCompanyBusinessUnitFileQuery
    {
        return SpyCompanyBusinessUnitFileQuery::create();
    }

    public function createSspAssetFileQuery(): SpySspAssetFileQuery
    {
        return SpySspAssetFileQuery::create();
    }

    public function getSspInquirySspAssetQuery(): SpySspInquirySspAssetQuery
    {
        return SpySspInquirySspAssetQuery::create();
    }

    protected function getSspAssetSearchQuery(): SpySspAssetSearchQuery
    {
        return SpySspAssetSearchQuery::create();
    }
}
