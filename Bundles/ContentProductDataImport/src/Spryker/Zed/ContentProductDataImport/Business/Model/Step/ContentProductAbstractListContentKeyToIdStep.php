<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\Step;

use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\ContentProductDataImport\Business\Model\DataSet\ContentProductAbstractListDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentProductAbstractListContentKeyToIdStep implements DataImportStepInterface
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
        $contentEntity = SpyContentQuery::create()
            ->filterByKey($dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PROCUCT_ABSTRACT_LIST_KEY])
            ->findOneOrCreate();

        $contentEntity->setName($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_NAME])
            ->setDescription($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_DESCRIPTION]);

        $contentEntity->setContentTypeKey(static::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST)
            ->setContentTermKey(static::CONTENT_TERM_PRODUCT_ABSTRACT_LIST);

        $contentEntity->save();

        $dataSet[ContentProductAbstractListDataSetInterface::COLUMN_ID_CONTENT] = $contentEntity->getIdContent();
    }
}
