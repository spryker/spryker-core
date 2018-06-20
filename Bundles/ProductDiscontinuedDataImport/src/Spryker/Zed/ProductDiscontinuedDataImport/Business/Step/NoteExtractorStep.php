<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business\Step;

use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\DataSet\ProductDiscontinuedDataSetInterface;

class NoteExtractorStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $localizedNotes = [];
        foreach ($dataSet[ProductDiscontinuedDataSetInterface::KEY_LOCALES] as $localeName => $idLocale) {
            $key = ProductDiscontinuedDataSetInterface::KEY_NOTE . '.' . $localeName;
            if (!isset($dataSet[$key])) {
                throw new InvalidDataException(
                    sprintf('Could not find note for locale "%s" and sku "%s"', $localeName, $dataSet[ProductDiscontinuedDataSetInterface::KEY_CONCRETE_SKU])
                );
            }
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
