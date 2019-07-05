<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetDataImport\Business\Model;

use Orm\Zed\Content\Persistence\Map\SpyContentLocalizedTableMap;
use Orm\Zed\Content\Persistence\SpyContent;
use Orm\Zed\Content\Persistence\SpyContentLocalizedQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\Content\Dependency\ContentEvents;
use Spryker\Zed\ContentProductSetDataImport\Business\Model\DataSet\ContentProductSetDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentProductSetWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\ContentProductSet\ContentProductSetConfig::CONTENT_TYPE_PRODUCT_SET
     */
    protected const CONTENT_TYPE_PRODUCT_SET = 'Product Set';

    /**
     * @uses \Spryker\Shared\ContentProductSet\ContentProductSetConfig::CONTENT_TERM_PRODUCT_SET
     */
    protected const CONTENT_TERM_PRODUCT_SET = 'Product Set';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $contentProductSetEntity = $this->saveContentProductSetEntity($dataSet);

        SpyContentLocalizedQuery::create()
            ->filterByFkContent($contentProductSetEntity->getIdContent())
            ->find()
            ->delete();

        foreach ($dataSet[ContentProductSetDataSetInterface::CONTENT_LOCALIZED_PRODUCT_SET_TERMS] as $localizedProductSetTermParameters) {
            $contentLocalizedProductSetEntity = SpyContentLocalizedQuery::create()
                ->filterByFkContent($contentProductSetEntity->getIdContent())
                ->filterByFkLocale($localizedProductSetTermParameters[SpyContentLocalizedTableMap::COL_FK_LOCALE])
                ->findOneOrCreate();

            $contentLocalizedProductSetEntity->fromArray($localizedProductSetTermParameters, SpyContentLocalizedTableMap::TYPE_COLNAME);

            $contentLocalizedProductSetEntity->save();
        }

        $this->addPublishEvents(
            ContentEvents::CONTENT_PUBLISH,
            $contentProductSetEntity->getPrimaryKey()
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Content\Persistence\SpyContent
     */
    protected function saveContentProductSetEntity(DataSetInterface $dataSet): SpyContent
    {
        $contentProductSetEntity = SpyContentQuery::create()
            ->filterByKey($dataSet[ContentProductSetDataSetInterface::COLUMN_KEY])
            ->findOneOrCreate();

        $contentProductSetEntity->setName($dataSet[ContentProductSetDataSetInterface::COLUMN_NAME])
            ->setDescription($dataSet[ContentProductSetDataSetInterface::COLUMN_DESCRIPTION])
            ->setContentTypeKey(static::CONTENT_TYPE_PRODUCT_SET)
            ->setContentTermKey(static::CONTENT_TERM_PRODUCT_SET)
            ->save();

        return $contentProductSetEntity;
    }
}
