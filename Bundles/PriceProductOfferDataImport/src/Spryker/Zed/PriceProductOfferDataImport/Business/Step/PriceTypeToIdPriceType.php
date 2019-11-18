<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class PriceTypeToIdPriceType implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idPriceTypeCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceTypeName = $dataSet[PriceProductOfferDataSetInterface::PRICE_TYPE];

        if (!isset($this->idPriceTypeCache[$priceTypeName])) {
            $priceTypeQuery = SpyPriceTypeQuery::create();
            $priceTypeQuery->select(SpyPriceTypeTableMap::COL_ID_PRICE_TYPE);
            $idPriceType = $priceTypeQuery->findOneByName($priceTypeName);

            if (!$idPriceType) {
                throw new EntityNotFoundException(sprintf('Could not find price type by name "%s"', $idPriceType));
            }

            $this->idPriceTypeCache[$priceTypeName] = $idPriceType;
        }

        $dataSet[PriceProductOfferDataSetInterface::FK_PRICE_TYPE] = $this->idPriceTypeCache[$priceTypeName];
    }
}
