<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model;

use Orm\Zed\Content\Persistence\Map\SpyContentLocalizedTableMap;
use Orm\Zed\Content\Persistence\SpyContent;
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
     * @uses \Spryker\Shared\ContentProduct\ContentProductConfig::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST
     */
    protected const CONTENT_TYPE_PRODUCT_ABSTRACT_LIST = 'Abstract Product List';

    /**
     * @uses \Spryker\Shared\ContentProduct\ContentProductConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST
     */
    protected const CONTENT_TERM_PRODUCT_ABSTRACT_LIST = 'Abstract Product List';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $contentEntity = $this->saveContentEntity($dataSet);

        SpyContentLocalizedQuery::create()
            ->filterByFkContent($contentEntity->getIdContent())
            ->find()
            ->delete();

        foreach ($dataSet[ContentProductAbstractListDataSetInterface::CONTENT_LOCALIZED_ITEMS] as $localizedItem) {
            $contentLocalizedEntity = SpyContentLocalizedQuery::create()
                ->filterByFkContent($contentEntity->getIdContent())
                ->filterByFkLocale($localizedItem[SpyContentLocalizedTableMap::COL_FK_LOCALE])
                ->findOneOrCreate();

            $contentLocalizedEntity->fromArray($localizedItem, SpyContentLocalizedTableMap::TYPE_COLNAME);

            $contentLocalizedEntity->save();
        }

        $this->addPublishEvents(
            ContentEvents::CONTENT_PUBLISH,
            $contentEntity->getPrimaryKey()
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Content\Persistence\SpyContent
     */
    protected function saveContentEntity(DataSetInterface $dataSet): SpyContent
    {
        $contentEntity = SpyContentQuery::create()
            ->filterByKey($dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PRODUCT_ABSTRACT_LIST_KEY])
            ->findOneOrCreate();

        $contentEntity->setName($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_NAME])
            ->setDescription($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_DESCRIPTION])
            ->setContentTypeKey(static::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST)
            ->setContentTermKey(static::CONTENT_TERM_PRODUCT_ABSTRACT_LIST)
            ->save();

        return $contentEntity;
    }
}
