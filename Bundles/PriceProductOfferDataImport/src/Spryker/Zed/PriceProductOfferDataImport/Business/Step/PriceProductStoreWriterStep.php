<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class PriceProductStoreWriterStep implements DataImportStepInterface
{
    protected const VALUE_NET = PriceProductOfferDataSetInterface::VALUE_NET;

    protected const VALUE_GROSS = PriceProductOfferDataSetInterface::VALUE_GROSS;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[PriceProductOfferDataSetInterface::FK_PRICE_PRODUCT_STORE] = $this->getIdPriceProductStore($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string
     */
    protected function getIdPriceProductStore(DataSetInterface $dataSet): string
    {
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore|null $priceProductStoreEntity */
        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($dataSet[PriceProductOfferDataSetInterface::FK_STORE])
            ->filterByFkCurrency($dataSet[PriceProductOfferDataSetInterface::FK_CURRENCY])
            ->filterByFkPriceProduct($dataSet[PriceProductOfferDataSetInterface::FK_PRICE_PRODUCT])
            ->useSpyPriceProductOfferQuery()
                ->useSpyProductOfferQuery()
                    ->filterByProductOfferReference($dataSet[PriceProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE])
                ->endUse()
            ->endUse()
            ->findOne();

        if (!$priceProductStoreEntity) {
            $priceProductStoreEntity = (new SpyPriceProductStore())
                ->setFkStore($dataSet[PriceProductOfferDataSetInterface::FK_STORE])
                ->setFkCurrency($dataSet[PriceProductOfferDataSetInterface::FK_CURRENCY])
                ->setFkPriceProduct($dataSet[PriceProductOfferDataSetInterface::FK_PRICE_PRODUCT]);
        }

        $priceProductStoreEntity
            ->setNetPrice($this->castPriceValue($dataSet[static::VALUE_NET]))
            ->setGrossPrice($this->castPriceValue($dataSet[static::VALUE_GROSS]))
            ->setPriceData($dataSet[PriceProductOfferDataSetInterface::KEY_PRICE_DATA])
            ->setPriceDataChecksum($dataSet[PriceProductOfferDataSetInterface::KEY_PRICE_DATA_CHECKSUM]);

        $priceProductStoreEntity->save();

        return $priceProductStoreEntity->getIdPriceProductStore();
    }

    /**
     * @param string $priceValue
     *
     * @return int|null
     */
    protected function castPriceValue(string $priceValue): ?int
    {
        if (trim($priceValue) === '') {
            return null;
        }

        return (int)$priceValue;
    }
}
