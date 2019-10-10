<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;

class MerchantProductOfferStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (empty($dataSet[MerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE])) {
            throw new EntityNotFoundException('Product offer reference is a required field');
        }

        $spyProductOffer = new SpyProductOffer();
        $spyProductOffer->setFkMerchant($dataSet[MerchantProductOfferDataSetInterface::FK_MERCHANT]);
        $spyProductOffer->setConcreteSku($dataSet[MerchantProductOfferDataSetInterface::CONCRETE_SKU]);
        $spyProductOffer->setProductOfferReference($dataSet[MerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE]);
        $spyProductOffer->save();
    }
}
