<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerDataImport\Business\Model\Step;

use Generated\Shared\Transfer\ContentBannerTransfer;
use Spryker\Zed\ContentBannerDataImport\Business\Model\DataSet\ContentBannerDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class PrepareLocalizedItemsStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $localizedAttributes = [];

        $dataSet[AddLocalesStep::KEY_LOCALES] = array_merge($dataSet[AddLocalesStep::KEY_LOCALES], ['default' => null]);

        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $attributes = [];
            $localeNotEmpty = false;
            $contentBanner = new ContentBannerTransfer();
            $properties = $contentBanner->toArray();

            foreach (array_keys($properties) as $attributeName) {
                if (!empty($dataSet[$attributeName . '.' . $localeName])) {
                    $attributes[$attributeName] = $dataSet[$attributeName . '.' . $localeName];
                    $localeNotEmpty = true;
                }
            }
            if ($localeNotEmpty) {
                $localizedAttributes[$idLocale] = $contentBanner->fromArray($attributes);
            }
        }

        $dataSet[ContentBannerDataSetInterface::CONTENT_LOCALIZED_ITEMS] = $localizedAttributes;
    }
}
