<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspAssetManagement\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\SspAssetBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAsset;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetToCompanyBusinessUnit;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquirySspAsset;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquirySspAssetQuery;
use Ramsey\Uuid\Nonstandard\Uuid;
use Spryker\Service\UtilDateTime\UtilDateTimeService;
use Spryker\Zed\FileManager\Business\FileManagerFacade;
use SprykerFeature\Zed\SspAssetManagement\Persistence\Mapper\SspAssetMapper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class SspAssetDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

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
        foreach ($sspAssetTransfer->getAssignments() as $assignment) {
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
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $businessUnitTransfer
     *
     * @return void
     */
    protected function haveSspAssetToCompanyBusinessUnit(SspAssetTransfer $sspAssetTransfer, CompanyBusinessUnitTransfer $businessUnitTransfer): void
    {
        $sspAssetToCompanyBusinessUnit = new SpySspAssetToCompanyBusinessUnit();
        $sspAssetToCompanyBusinessUnit->setFkSspAsset($sspAssetTransfer->getIdSspAsset());
        $sspAssetToCompanyBusinessUnit->setFkCompanyBusinessUnit($businessUnitTransfer->getIdCompanyBusinessUnit());
        $sspAssetToCompanyBusinessUnit->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($sspAssetToCompanyBusinessUnit): void {
            $sspAssetToCompanyBusinessUnit->delete();
        });
    }
}
