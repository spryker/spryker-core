<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageDataImport\Business\CmsPage;

use Spryker\Zed\CmsPageDataImport\Business\DataSet\CmsPageDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class PlaceholderExtractorStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $placeholderNames;

    /**
     * @param array $attributeNames
     */
    public function __construct(array $attributeNames)
    {
        $this->placeholderNames = $attributeNames;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $localizedPlaceholder = [];
        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $placeholder = [];

            foreach ($this->placeholderNames as $placeholderName) {
                $key = str_replace('placeholder.', '', $placeholderName);
                $placeholder[$key] = $dataSet[$placeholderName . '.' . $localeName];
            }

            $localizedPlaceholder[$idLocale] = $placeholder;
        }

        $dataSet[CmsPageDataSet::KEY_PLACEHOLDER] = $localizedPlaceholder;
    }
}
