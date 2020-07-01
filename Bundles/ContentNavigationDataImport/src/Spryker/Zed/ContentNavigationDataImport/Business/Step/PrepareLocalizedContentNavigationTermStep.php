<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationDataImport\Business\Step;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Spryker\Zed\ContentNavigationDataImport\Business\DataSet\ContentNavigationDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class PrepareLocalizedContentNavigationTermStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $localizedNavigationTermParameters = [];

        $dataSet[AddLocalesStep::KEY_LOCALES] = array_merge($dataSet[AddLocalesStep::KEY_LOCALES], ['default' => null]);

        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $navigationTermParameters = [];
            $localeNotEmpty = false;
            $contentNavigationTermTransfer = new ContentNavigationTermTransfer();

            foreach (array_keys($contentNavigationTermTransfer->toArray()) as $navigationTermParameterKey) {
                $navigationTermParameterKeyForLocale = $navigationTermParameterKey . '.' . $localeName;
                if (empty($dataSet[$navigationTermParameterKeyForLocale])) {
                    continue;
                }

                $navigationTermParameters[$navigationTermParameterKey] = $dataSet[$navigationTermParameterKeyForLocale];
                $localeNotEmpty = true;
            }
            if ($localeNotEmpty) {
                $localizedNavigationTermParameters[$idLocale] = $contentNavigationTermTransfer->fromArray($navigationTermParameters);
            }
        }

        $dataSet[ContentNavigationDataSetInterface::CONTENT_LOCALIZED_NAVIGATION_TERMS] = $localizedNavigationTermParameters;
    }
}
