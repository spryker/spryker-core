<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CmsSlotBlockDataImport;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CmsSlotBlockConditionTransfer;
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

        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())
            ->setIdSlotTemplate($cmsSlotBlockEntity->getFkCmsSlotTemplate())
            ->setIdSlot($cmsSlotBlockEntity->getFkCmsSlot())
            ->setIdCmsBlock($cmsSlotBlockEntity->getFkCmsBlock())
            ->setPosition($cmsSlotBlockEntity->getPosition());

        if ($cmsSlotBlockEntity->getConditions()) {
            $conditions = json_decode($cmsSlotBlockEntity->getConditions(), true);
            $cmsSlotBlockConditionTransfers = new ArrayObject();

            foreach ($conditions as $condition) {
                if ($condition) {
                    $cmsSlotBlockConditionTransfers->append((new CmsSlotBlockConditionTransfer())->fromArray($condition, true));
                }
            }

            if ($cmsSlotBlockConditionTransfers) {
                $cmsSlotBlockTransfer->setConditions($cmsSlotBlockConditionTransfers);
            }
        }

        return $cmsSlotBlockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CmsSlotBlockConditionTransfer[]
     */
    public function getCmsSlotBlockConditions(
        CategoryTransfer $categoryTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ArrayObject {
        $conditions = new ArrayObject();

        $conditionCategory = (new CmsSlotBlockConditionTransfer())->fromArray([
            'all' => false,
            'categoryIds' => [$categoryTransfer->getIdCategory()],
        ], true);

        $conditionProductCategory = (new CmsSlotBlockConditionTransfer())->fromArray([
            'all' => false,
            'productIds' => [$productAbstractTransfer->getIdProductAbstract()],
            'categoryIds' => [$categoryTransfer->getIdCategory()],
        ], true);

        $conditionCmsPage = (new CmsSlotBlockConditionTransfer())->fromArray([
            'all' => true,
            'pageIds' => [],
        ], true);

        $conditions->append($conditionCategory);
        $conditions->append($conditionProductCategory);
        $conditions->append($conditionCmsPage);

        return $conditions;
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
