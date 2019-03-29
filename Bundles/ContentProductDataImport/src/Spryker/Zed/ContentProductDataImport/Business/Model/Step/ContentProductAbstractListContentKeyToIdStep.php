<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\Step;

use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\ContentProductDataImport\Business\Model\DataSet\ContentProductAbstractListDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentProductAbstractListContentKeyToIdStep implements DataImportStepInterface
{
    protected const LOCALE_NAME_DEFAULT = 'default';

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
        if (!$this->isNotEmptyAllSkuFields($dataSet)) {
            $dataSet[ContentProductAbstractListDataSetInterface::COLUMN_ID_CONTENT] = null;
            return;
        }

        $contentEntity = SpyContentQuery::create()
            ->filterByKey($dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PRODUCT_ABSTRACT_LIST_KEY])
            ->findOneOrCreate();

        $contentEntity->setName($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_NAME])
            ->setDescription($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_DESCRIPTION])
            ->setContentTypeKey(static::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST)
            ->setContentTermKey(static::CONTENT_TERM_PRODUCT_ABSTRACT_LIST);

        $contentEntity->save();

        $dataSet[ContentProductAbstractListDataSetInterface::COLUMN_ID_CONTENT] = $contentEntity->getIdContent();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isNotEmptyAllSkuFields(DataSetInterface $dataSet): bool
    {
        $dataSet[AddLocalesStep::KEY_LOCALES] = array_merge($dataSet[AddLocalesStep::KEY_LOCALES], [static::LOCALE_NAME_DEFAULT => null]);
        $countEmptySkuFields = 0;
        $countSkuFields = 0;

        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $skusLocaleKey = ContentProductAbstractListDataSetInterface::COLUMN_SKUS . '.' . $localeName;

            if (isset($dataSet[$skusLocaleKey])) {
                $countSkuFields++;

                if (!$dataSet[$skusLocaleKey]) {
                    $countEmptySkuFields++;
                }
            }
        }

        if ($countSkuFields > 0 && $countSkuFields > $countEmptySkuFields) {
            return true;
        }

        return false;
    }
}
