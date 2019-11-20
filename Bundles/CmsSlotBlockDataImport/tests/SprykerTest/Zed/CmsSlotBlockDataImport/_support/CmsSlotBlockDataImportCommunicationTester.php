<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotBlockDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class CmsSlotBlockDataImportCommunicationTester extends Actor
{
    use _generated\CmsSlotBlockDataImportCommunicationTesterActions;

    /**
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function createDataImporterConfigurationTransfer(string $fileName): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName($fileName);

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        return $dataImportConfigurationTransfer;
    }

    /**
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer|null
     */
    public function findCmsSlotBlock(int $idCmsSlotTemplate, int $idCmsSlot): ?CmsSlotBlockTransfer
    {
        $cmsSlotBlockEntity = SpyCmsSlotBlockQuery::create()
            ->filterByFkCmsSlot($idCmsSlot)
            ->filterByFkCmsSlotTemplate($idCmsSlotTemplate)
            ->findOne();

        if (!$cmsSlotBlockEntity) {
            return null;
        }

        return (new CmsSlotBlockTransfer())
            ->fromArray($cmsSlotBlockEntity->toArray(), true)
            ->setIdSlotTemplate($cmsSlotBlockEntity->getFkCmsSlotTemplate())
            ->setIdSlot($cmsSlotBlockEntity->getFkCmsSlot())
            ->setIdCmsBlock($cmsSlotBlockEntity->getFkCmsBlock())
            ->setConditions(json_decode($cmsSlotBlockEntity->getConditions(), true));
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array
     */
    public function getCmsSlotBlockConditions(
        CategoryTransfer $categoryTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): array {
        return [
            'category' => [
                'all' => true,
                'categoryIds' => [$categoryTransfer->getIdCategory()],
            ],
            'productCategory' => [
                'all' => false,
                'productIds' => [$productAbstractTransfer->getIdProductAbstract()],
                'categoryIds' => [$categoryTransfer->getIdCategory()],
            ],
            'cms_page' => [
                'all' => true,
            ],
        ];
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function removeCmsBlockByKey(string $key): void
    {
        SpyCmsBlockQuery::create()->filterByKey($key)->delete();
    }
}
