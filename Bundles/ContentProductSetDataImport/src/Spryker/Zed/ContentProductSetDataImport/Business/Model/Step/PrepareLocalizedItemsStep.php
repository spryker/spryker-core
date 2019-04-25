<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetDataImport\Business\Model\Step;

use Generated\Shared\Transfer\ContentProductSetTermTransfer;
use Orm\Zed\Content\Persistence\Map\SpyContentLocalizedTableMap;
use Spryker\Zed\ContentProductSetDataImport\Business\Model\DataSet\ContentProductSetDataSetInterface;
use Spryker\Zed\ContentProductSetDataImport\Dependency\Service\ContentProductSetDataImportToUtilEncodingServiceInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class PrepareLocalizedItemsStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ContentProductSetDataImport\Dependency\Service\ContentProductSetDataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ContentProductSetDataImport\Dependency\Service\ContentProductSetDataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ContentProductSetDataImportToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[AddLocalesStep::KEY_LOCALES] = array_merge($dataSet[AddLocalesStep::KEY_LOCALES], ['default' => null]);

        $localizedProductSetTermParameters = [];

        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $localizedColumnId = ContentProductSetDataSetInterface::COLUMN_PRODUCT_SET_ID . '.' . $localeName;

            if (!isset($dataSet[$localizedColumnId]) || !$dataSet[$localizedColumnId]) {
                continue;
            }

            $localizedProductSetTermParameters[] = [
                SpyContentLocalizedTableMap::COL_FK_LOCALE => $idLocale,
                SpyContentLocalizedTableMap::COL_PARAMETERS => $this->utilEncodingService->encodeJson(
                    (new ContentProductSetTermTransfer())->setIdProductSet($dataSet[$localizedColumnId])->toArray()
                ),
            ];
        }

        $dataSet[ContentProductSetDataSetInterface::CONTENT_LOCALIZED_PRODUCT_SET_TERMS] = $localizedProductSetTermParameters;
    }
}
