<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspInquiryManagement\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\SspInquiryBuilder;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryFile;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryFileQuery;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquirySalesOrder;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\FileManager\Business\FileManagerFacade;
use SprykerFeature\Zed\SspInquiryManagement\Persistence\Mapper\SspInquiryMapper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class SspInquiryDataHelper extends Module
{
    use DataCleanupHelperTrait;

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
        $this->generateAndSaveSspInquiryImages($seedData['fileAmount'] ?? 0, $sspInquiryTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($sspInquiryTransfer): void {
            $this->debug(sprintf('Deleting Ssp Inquiry: %s', $sspInquiryTransfer->getIdSspInquiry()));
            SpySspInquiryFileQuery::create()->filterByFkSspInquiry($sspInquiryTransfer->getIdSspInquiry())->delete();
            SpySspInquiryQuery::create()->filterByIdSspInquiry($sspInquiryTransfer->getIdSspInquiry())->delete();
        });

        return $sspInquiryTransfer;
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
     * Generates a small image.
     *
     * @return array<string, string>
     */
    protected function generateSmallFile(): array
    {
        $extensions = ['png', 'jpg', 'jpeg', 'pdf'];
        $extension = $extensions[array_rand($extensions)];
        $size = rand(1, 200 * 1024); // Random size from 1B to 200kB

        $imageContent = '';
        $type = '';
        switch ($extension) {
            case 'png':
            case 'jpg':
            case 'jpeg':
                $image = imagecreatetruecolor(100, 100);
                $backgroundColor = imagecolorallocate($image, 255, 255, 255);
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
            case 'pdf':
                $imageContent = str_repeat('A', $size); // Simulate a PDF file with random content
                $type = 'application/pdf';

                break;
        }

        // Adjust the size to the desired random size
        if (strlen($imageContent) > $size) {
            $imageContent = substr($imageContent, 0, $size);
        } else {
            $imageContent = str_pad($imageContent, $size, ' ');
        }

        return ['content' => $imageContent, 'extension' => $extension, 'type' => $type];
    }
}
