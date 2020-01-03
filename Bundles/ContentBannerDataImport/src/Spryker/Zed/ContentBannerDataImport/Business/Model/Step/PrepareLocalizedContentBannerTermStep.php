<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerDataImport\Business\Model\Step;

use Generated\Shared\Transfer\ContentBannerTermTransfer;
use Spryker\Zed\ContentBannerDataImport\Business\Model\DataSet\ContentBannerDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class PrepareLocalizedContentBannerTermStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $localizedBannerTermParameters = [];

        $dataSet[AddLocalesStep::KEY_LOCALES] = array_merge($dataSet[AddLocalesStep::KEY_LOCALES], ['default' => null]);

        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $bannerTermParamaters = [];
            $localeNotEmpty = false;
            $contentBanner = new ContentBannerTermTransfer();

            foreach (array_keys($contentBanner->toArray()) as $bannerTermParamaterKey) {
                if (!empty($dataSet[$bannerTermParamaterKey . '.' . $localeName])) {
                    $bannerTermParamaters[$bannerTermParamaterKey] = $dataSet[$bannerTermParamaterKey . '.' . $localeName];
                    $localeNotEmpty = true;
                }
            }
            if ($localeNotEmpty) {
                $localizedBannerTermParameters[$idLocale] = $contentBanner->fromArray($bannerTermParamaters);
            }
        }

        $dataSet[ContentBannerDataSetInterface::CONTENT_LOCALIZED_BANNER_TERMS] = $localizedBannerTermParameters;
    }
}
