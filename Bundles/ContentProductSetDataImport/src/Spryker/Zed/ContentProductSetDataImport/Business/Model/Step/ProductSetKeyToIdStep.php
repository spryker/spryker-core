<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetDataImport\Business\Model\Step;

use Orm\Zed\ProductSet\Persistence\SpyProductSetQuery;
use Spryker\Zed\ContentProductSetDataImport\Business\Model\DataSet\ContentProductSetDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductSetKeyToIdStep implements DataImportStepInterface
{
    protected const ERROR_MESSAGE_PRODUCT_SET_KEY_DEFAULT = '"{column}" is required. Please check the row with key: "{key}".';
    protected const ERROR_MESSAGE_PRODUCT_SET_WRONG_KEY = 'Please check "{column}" in the row with key: "{key}". The wrong product set key passed.';
    protected const ERROR_MESSAGE_PARAMETER_COLUMN = '{column}';
    protected const ERROR_MESSAGE_PARAMETER_KEY = '{key}';

    /**
     * @var \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    protected $productSetQuery;

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery $productSetQuery
     */
    public function __construct(SpyProductSetQuery $productSetQuery)
    {
        $this->productSetQuery = $productSetQuery;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->assureDefaultProductSetKeyExists($dataSet);

        $dataSet[AddLocalesStep::KEY_LOCALES] = array_merge($dataSet[AddLocalesStep::KEY_LOCALES], ['default' => null]);

        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $productSetKeyLocale = ContentProductSetDataSetInterface::COLUMN_PRODUCT_SET_KEY . '.' . $localeName;

            if (!isset($dataSet[$productSetKeyLocale]) || !$dataSet[$productSetKeyLocale]) {
                continue;
            }

            $productSetIdLocale = ContentProductSetDataSetInterface::COLUMN_PRODUCT_SET_ID . '.' . $localeName;
            $dataSet[$productSetIdLocale] = $this->getIdProductSet($dataSet, $productSetKeyLocale);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function assureDefaultProductSetKeyExists(DataSetInterface $dataSet): void
    {
        if (!isset($dataSet[ContentProductSetDataSetInterface::COLUMN_PRODUCT_SET_KEY_DEFAULT])
            || !$dataSet[ContentProductSetDataSetInterface::COLUMN_PRODUCT_SET_KEY_DEFAULT]
        ) {
            $parameters = [
                static::ERROR_MESSAGE_PARAMETER_KEY => $dataSet[ContentProductSetDataSetInterface::COLUMN_KEY],
                static::ERROR_MESSAGE_PARAMETER_COLUMN => ContentProductSetDataSetInterface::COLUMN_PRODUCT_SET_KEY_DEFAULT,
            ];

            $this->createInvalidDataImportException(static::ERROR_MESSAGE_PRODUCT_SET_KEY_DEFAULT, $parameters);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $productSetKeyColumn
     *
     * @return int
     */
    protected function getIdProductSet(DataSetInterface $dataSet, string $productSetKeyColumn): int
    {
        $productSetEntity = $this->productSetQuery
            ->clear()
            ->findOneByProductSetKey($dataSet[$productSetKeyColumn]);

        if ($productSetEntity) {
            return $productSetEntity->getIdProductSet();
        }

        $parameters = [
            static::ERROR_MESSAGE_PARAMETER_KEY => $dataSet[ContentProductSetDataSetInterface::COLUMN_KEY],
            static::ERROR_MESSAGE_PARAMETER_COLUMN => $productSetKeyColumn,
        ];

        $this->createInvalidDataImportException(static::ERROR_MESSAGE_PRODUCT_SET_WRONG_KEY, $parameters);
    }

    /**
     * @param string $message
     * @param array $parameters
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function createInvalidDataImportException(string $message, array $parameters = []): void
    {
        $errorMessage = strtr($message, $parameters);

        throw new InvalidDataException($errorMessage);
    }
}
