<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model;

use Orm\Zed\Content\Persistence\Map\SpyContentLocalizedTableMap;
use Orm\Zed\Content\Persistence\SpyContentLocalizedQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\Content\Dependency\ContentEvents;
use Spryker\Zed\ContentProductDataImport\Business\Model\DataSet\ContentProductAbstractListDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentProductAbstractListWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        SpyContentLocalizedQuery::create()
            ->filterByFkContent($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_ID_CONTENT])
            ->find()
            ->delete();

        foreach ($dataSet[ContentProductAbstractListDataSetInterface::CONTENT_LOCALIZED_ITEMS] as $localizedItem) {
            $contentLocalizedEntity = SpyContentLocalizedQuery::create()
                ->filterByFkContent($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_ID_CONTENT])
                ->filterByFkLocale($localizedItem[SpyContentLocalizedTableMap::COL_FK_LOCALE])
                ->findOneOrCreate();

            $contentLocalizedEntity->fromArray($localizedItem, SpyContentLocalizedTableMap::TYPE_COLNAME);

            $contentLocalizedEntity->save();
        }

        $contentEntity = SpyContentQuery::create()
            ->filterByKey($dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PRODUCT_ABSTRACT_LIST_KEY])
            ->findOneOrCreate();

        $this->addPublishEvents(
            ContentEvents::CONTENT_PUBLISH,
            $contentEntity->getPrimaryKey()
        );
    }
}
