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
    public function execute(DataSetInterface $dataSet): void
    {
        $contentProductAbstractListEntity = $this->saveContentProductAbstractListEntity($dataSet);

        SpyContentLocalizedQuery::create()
            ->filterByFkContent($contentProductAbstractListEntity->getIdContent())
            ->find()
            ->delete();

        foreach ($dataSet[ContentProductAbstractListDataSetInterface::CONTENT_LOCALIZED_PRODUCT_ABSTRACT_LIST_TERMS] as $localizedProductAbstractListTermParameters) {
            $contentLocalizedProductAbstractListEntity = SpyContentLocalizedQuery::create()
                ->filterByFkContent($contentProductAbstractListEntity->getIdContent())
                ->filterByFkLocale($localizedProductAbstractListTermParameters[SpyContentLocalizedTableMap::COL_FK_LOCALE])
                ->findOneOrCreate();

            $contentLocalizedProductAbstractListEntity->fromArray($localizedProductAbstractListTermParameters, SpyContentLocalizedTableMap::TYPE_COLNAME);

            $contentLocalizedProductAbstractListEntity->save();
        }

        $this->addPublishEvents(
            ContentEvents::CONTENT_PUBLISH,
            $contentProductAbstractListEntity->getPrimaryKey()
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Content\Persistence\SpyContent
     */
    protected function saveContentProductAbstractListEntity(DataSetInterface $dataSet): SpyContent
    {
        $contentProductAbstractListEntity = SpyContentQuery::create()
            ->filterByKey($dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PRODUCT_ABSTRACT_LIST_KEY])
            ->findOneOrCreate();

        $contentProductAbstractListEntity->setName($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_NAME])
            ->setDescription($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_DESCRIPTION])
            ->setContentTypeKey(static::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST)
            ->setContentTermKey(static::CONTENT_TERM_PRODUCT_ABSTRACT_LIST)
            ->save();

        return $contentProductAbstractListEntity;
    }
}
