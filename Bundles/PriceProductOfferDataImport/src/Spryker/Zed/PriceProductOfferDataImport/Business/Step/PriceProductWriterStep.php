<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class PriceProductWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $productPriceEntity */
        $productPriceEntity = SpyPriceProductQuery::create()
            ->filterByFkPriceType($dataSet[PriceProductOfferDataSetInterface::FK_PRICE_TYPE])
            ->filterByFkProduct($dataSet[PriceProductOfferDataSetInterface::ID_PRODUCT_CONCRETE])
            ->findOneOrCreate();

        if ($productPriceEntity->isNew()) {
            $productPriceEntity->save();
        }

        $dataSet[PriceProductOfferDataSetInterface::FK_PRICE_PRODUCT] = $productPriceEntity->getIdPriceProduct();
    }
}
