<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class PriceProductWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceProductQuery = SpyPriceProductQuery::create();
        $priceProductQuery->filterByFkPriceType($dataSet[PriceProductOfferDataSetInterface::FK_PRICE_TYPE]);
        $priceProductQuery->filterByFkProduct($dataSet[PriceProductOfferDataSetInterface::ID_PRODUCT_CONCRETE]);

        $productPriceEntity = $priceProductQuery->findOneOrCreate();
        $productPriceEntity->save();

        $dataSet[PriceProductOfferDataSetInterface::FK_PRICE_PRODUCT] = $productPriceEntity->getIdPriceProduct();
    }
}
