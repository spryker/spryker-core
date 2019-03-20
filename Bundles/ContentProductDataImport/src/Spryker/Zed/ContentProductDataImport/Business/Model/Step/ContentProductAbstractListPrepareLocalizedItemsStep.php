<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\Step;

use Generated\Shared\Transfer\ContentProductAbstractListTransfer;
use Orm\Zed\Content\Persistence\Map\SpyContentLocalizedTableMap;
use Spryker\Zed\ContentProductDataImport\Business\Model\DataSet\ContentProductAbstractListDataSetInterface;
use Spryker\Zed\ContentProductDataImport\Dependency\Service\ContentProductDataImportToUtilEncodingServiceInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentProductAbstractListPrepareLocalizedItemsStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ContentProductDataImport\Dependency\Service\ContentProductDataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ContentProductDataImport\Dependency\Service\ContentProductDataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ContentProductDataImportToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $contentLocalizedItems = [];

        foreach ($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_LOCALES] as $localeId => $localeName) {
            $idsLocaleKey = ContentProductAbstractListDataSetInterface::COLUMN_IDS . '.' . $localeName;

            if (!isset($dataSet[$idsLocaleKey]) || !$dataSet[$idsLocaleKey]) {
                continue;
            }

            $localizedItem[SpyContentLocalizedTableMap::COL_FK_LOCALE] = $localeId > 0 ? $localeId : null;
            $localizedItem[SpyContentLocalizedTableMap::COL_FK_CONTENT] = $dataSet[ContentProductAbstractListDataSetInterface::COLUMN_ID_CONTENT];

            $contentProductAbstractListTransfer = (new ContentProductAbstractListTransfer())
                ->setIdProductAbstracts($dataSet[$idsLocaleKey]);

            $localizedItem[SpyContentLocalizedTableMap::COL_PARAMETERS] = $this->utilEncodingService->encodeJson(
                $contentProductAbstractListTransfer->toArray()
            );

            $contentLocalizedItems[] = $localizedItem;
        }

        $dataSet[ContentProductAbstractListDataSetInterface::CONTENT_LOCALIZED_ITEMS] = $contentLocalizedItems;
    }
}
