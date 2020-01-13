<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOffer\Dependency\PriceProductOfferEvents;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class PriceProductOfferWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceProductOfferEntity = SpyPriceProductOfferQuery::create()
            ->filterByFkProductOffer($dataSet[PriceProductOfferDataSetInterface::FK_PRODUCT_OFFER])
            ->filterByFkPriceType($dataSet[PriceProductOfferDataSetInterface::FK_PRICE_TYPE])
            ->filterByFkStore($dataSet[PriceProductOfferDataSetInterface::FK_STORE])
            ->filterByFkCurrency($dataSet[PriceProductOfferDataSetInterface::FK_CURRENCY])
            ->findOneOrCreate();

        $priceProductOfferEntity
            ->setNetPrice($dataSet[PriceProductOfferDataSetInterface::VALUE_NET])
            ->setGrossPrice($dataSet[PriceProductOfferDataSetInterface::VALUE_GROSS]);

        $priceProductOfferEntity->save();

        $this->addPublishEvents(PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH, $priceProductOfferEntity->getIdPriceProductOffer());
    }
}
