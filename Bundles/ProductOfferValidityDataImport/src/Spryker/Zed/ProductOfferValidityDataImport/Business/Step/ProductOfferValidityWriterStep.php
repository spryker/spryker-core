<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidityDataImport\Business\Step;

use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidityQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferValidityDataImport\Business\DataSet\ProductOfferValidityDataSetInterface;

class ProductOfferValidityWriterStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [
        ProductOfferValidityDataSetInterface::FK_PRODUCT_OFFER,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $productOfferValidityEntity = SpyProductOfferValidityQuery::create()
            ->filterByFkProductOffer($dataSet[ProductOfferValidityDataSetInterface::FK_PRODUCT_OFFER])
            ->findOneOrCreate();

        $productOfferValidityEntity
            ->setValidFrom($dataSet[ProductOfferValidityDataSetInterface::PRODUCT_VALID_FROM])
            ->setValidTo($dataSet[ProductOfferValidityDataSetInterface::PRODUCT_VALID_TO])
            ->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        foreach (static::REQUIRED_DATA_SET_KEYS as $requiredDataSetKey) {
            if (!isset($dataSet[$requiredDataSetKey])) {
                throw new InvalidDataException(sprintf('"%s" is required.', $requiredDataSetKey));
            }
        }
    }
}
