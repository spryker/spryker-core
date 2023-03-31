<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business\ProductDiscontinuedImportStep;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\ProductDiscontinuedDataSet\ProductDiscontinuedDataSetInterface;

class NoteExtractorStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $localizedNotes = [];
        foreach ($dataSet[ProductDiscontinuedDataSetInterface::KEY_LOCALES] as $localeName => $idLocale) {
            $key = ProductDiscontinuedDataSetInterface::KEY_NOTE . '.' . $localeName;
            if (empty($dataSet[$key])) {
                continue;
            }
            $localizedNotes[$idLocale] = [
                ProductDiscontinuedDataSetInterface::KEY_NOTE => $dataSet[$key],
            ];
        }

        $dataSet[ProductDiscontinuedDataSetInterface::KEY_LOCALIZED_NOTES] = $localizedNotes;
    }
}
