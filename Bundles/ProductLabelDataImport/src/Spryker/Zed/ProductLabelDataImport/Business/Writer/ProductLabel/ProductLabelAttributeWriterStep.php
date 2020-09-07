<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\DataSet\ProductLabelDataSetInterface;

class ProductLabelAttributeWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$dataSet[ProductLabelDataSetInterface::COL_ID_PRODUCT_LABEL]) {
            return;
        }

        foreach ($dataSet[LocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES] as $idLocale => $localizedAttributes) {
            $productLabelLocalizedAttributesEntity = SpyProductLabelLocalizedAttributesQuery::create()
                ->filterByFkProductLabel($dataSet[ProductLabelDataSetInterface::COL_ID_PRODUCT_LABEL])
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();

            $productLabelLocalizedAttributesEntity->setName($localizedAttributes[ProductLabelDataSetInterface::COL_NAME]);

            if ($productLabelLocalizedAttributesEntity->isNew() || $productLabelLocalizedAttributesEntity->isModified()) {
                $productLabelLocalizedAttributesEntity->save();
            }
        }
    }
}
