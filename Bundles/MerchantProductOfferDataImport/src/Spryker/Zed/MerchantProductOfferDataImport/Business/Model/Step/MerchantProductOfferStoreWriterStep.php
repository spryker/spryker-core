<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;

class MerchantProductOfferStoreWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferStoreEntity = SpyProductOfferStoreQuery::create()
            ->filterByFkStore($dataSet[MerchantProductOfferDataSetInterface::ID_STORE])
            ->filterByFkProductOffer($dataSet[MerchantProductOfferDataSetInterface::ID_PRODUCT_OFFER])
            ->findOneOrCreate();

        $productOfferStoreEntity->save();
    }
}
