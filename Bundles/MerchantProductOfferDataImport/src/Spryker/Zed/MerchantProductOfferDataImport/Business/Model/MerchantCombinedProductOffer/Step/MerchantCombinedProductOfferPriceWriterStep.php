<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddStoresStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\Trait\ProductGetterTrait;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\Trait\ProductOfferGetterTrait;
use Spryker\Zed\MerchantProductOfferDataImport\Dependency\MerchantProductOfferDataImportEvents;

class MerchantCombinedProductOfferPriceWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    use ProductGetterTrait;
    use ProductOfferGetterTrait;

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STORE_NOT_SUPPORTED = 'Store "%store%" is not supported.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PRICE_TYPE_NOT_SUPPORTED = 'Price type "%priceType%" is not supported.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CURRENCY_NOT_SUPPORTED = 'Currency "%currency%" is not supported.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CURRENCY_NOT_SUPPORTED_BY_STORE = 'Currency "%currency%" is not supported by "%store%" store.';

    /**
     * @var string
     */
    protected const PARAM_STORE = '%store%';

    /**
     * @var string
     */
    protected const PARAM_PRICE_TYPE = '%priceType%';

    /**
     * @var string
     */
    protected const PARAM_CURRENCY = '%currency%';

    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferPrices = $this->getProductOfferPrices($dataSet);

        if (!$productOfferPrices) {
            return;
        }

        $storeIdsIndexedByName = $this->getStoreIdsIndexedByName($dataSet);
        $priceTypeIdsIndexedByName = $this->getPriceTypeIdsIndexedByName($dataSet);
        $currencyIdsIndexedByCode = $this->getCurrencyIdsIndexedByCode($dataSet);

        $productEntity = $this->getProductFromDataSet($dataSet);
        $productOfferEntity = $this->getProductOfferFromDataSet($dataSet);

        foreach ($productOfferPrices as $priceData) {
            $this->validatePriceData($priceData, $dataSet);

            $idStore = $storeIdsIndexedByName[$priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_STORE]];
            $idCurrency = $currencyIdsIndexedByCode[$priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_CURRENCY]];
            $idPriceType = $priceTypeIdsIndexedByName[$priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_PRICE_TYPE]];

            $priceProductEntity = $this->persistPriceProduct($productEntity, $idPriceType);
            $priceProductStoreEntity = $this->persistPriceProductStore(
                $priceProductEntity,
                $idStore,
                $idCurrency,
                $priceData,
            );
            $this->persistPriceProductOffer($priceProductStoreEntity, $productOfferEntity, $productEntity);
        }
    }

    protected function persistPriceProduct(
        SpyProduct $productEntity,
        int $idPriceType
    ): SpyPriceProduct {
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery $priceProductQuery */
        $priceProductQuery = SpyPriceProductQuery::create();

        $priceProductEntity = $priceProductQuery
            ->filterByFkProduct($productEntity->getIdProduct())
            ->filterByFkPriceType($idPriceType)
            ->findOneOrCreate();

        if ($priceProductEntity->isNew() || $priceProductEntity->isModified()) {
            $priceProductEntity->save();

            /** @var int $idProduct */
            $idProduct = $priceProductEntity->getFkProduct();

            $this->addPublishEvents(MerchantProductOfferDataImportEvents::PRODUCT_CONCRETE_UPDATE, $idProduct);
        }

        return $priceProductEntity;
    }

    /**
     * @param array<string, mixed> $priceData
     */
    protected function persistPriceProductStore(
        SpyPriceProduct $priceProductEntity,
        int $idStore,
        int $idCurrency,
        array $priceData
    ): SpyPriceProductStore {
        /** @var \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery */
        $priceProductStoreQuery = SpyPriceProductStoreQuery::create();

        $priceProductStoreQuery
            ->filterByFkPriceProduct($priceProductEntity->getIdPriceProduct())
            ->filterByFkCurrency($idCurrency)
            ->filterByFkStore($idStore)
            ->filterByGrossPrice($priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_VALUE_GROSS])
            ->filterByNetPrice($priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_VALUE_NET]);

        $priceProductStoreEntity = $priceProductStoreQuery->findOneOrCreate();

        if ($priceProductStoreEntity->isNew() || $priceProductStoreEntity->isModified()) {
            $priceProductStoreEntity->save();

            $this->addPublishEvents(MerchantProductOfferDataImportEvents::PRODUCT_CONCRETE_UPDATE, (int)$priceProductEntity->getFkProduct());
        }

        return $priceProductStoreEntity;
    }

    protected function persistPriceProductOffer(
        SpyPriceProductStore $priceProductStoreEntity,
        SpyProductOffer $productOfferEntity,
        SpyProduct $productEntity
    ): void {
        /** @var \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery $priceProductOfferQuery */
        $priceProductOfferQuery = SpyPriceProductOfferQuery::create();

        $priceProductOfferEntity = $priceProductOfferQuery
            ->filterByFkPriceProductStore($priceProductStoreEntity->getIdPriceProductStore())
            ->filterByFkProductOffer($productOfferEntity->getIdProductOffer())
            ->findOneOrCreate();

        if ($priceProductOfferEntity->isNew() || $priceProductOfferEntity->isModified()) {
            $priceProductOfferEntity->save();

            $this->addPublishEvents(MerchantProductOfferDataImportEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH, (int)$priceProductOfferEntity->getIdPriceProductOffer());
            $this->addPublishEvents(MerchantProductOfferDataImportEvents::PRODUCT_CONCRETE_UPDATE, $productEntity->getIdProduct());
        }
    }

    /**
     * @param array<string, mixed> $priceData
     *
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function validatePriceData(array $priceData, DataSetInterface $dataSet): void
    {
        $storeIdsIndexedByName = $this->getStoreIdsIndexedByName($dataSet);
        $priceTypeIdsIndexedByName = $this->getPriceTypeIdsIndexedByName($dataSet);
        $currencyIdsIndexedByCode = $this->getCurrencyIdsIndexedByCode($dataSet);
        $currencyNamesIndexedByStoreName = $this->getCurrencyNamesIndexedByStoreName($dataSet);

        if (!isset($storeIdsIndexedByName[$priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_STORE]])) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_STORE_NOT_SUPPORTED)
                    ->setParameters([
                        static::PARAM_STORE => $priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_STORE],
                    ]),
            );
        }

        if (!isset($priceTypeIdsIndexedByName[$priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_PRICE_TYPE]])) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_PRICE_TYPE_NOT_SUPPORTED)
                    ->setParameters([
                        static::PARAM_PRICE_TYPE => $priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_PRICE_TYPE],
                    ]),
            );
        }

        $storeCurrencyNames = $currencyNamesIndexedByStoreName[$priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_STORE]];

        if (!isset($currencyIdsIndexedByCode[$priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_CURRENCY]])) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_CURRENCY_NOT_SUPPORTED)
                    ->setParameters([
                        static::PARAM_CURRENCY => $priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_CURRENCY],
                    ]),
            );
        }

        if (!in_array($priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_CURRENCY], $storeCurrencyNames, true)) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_CURRENCY_NOT_SUPPORTED_BY_STORE)
                    ->setParameters([
                        static::PARAM_CURRENCY => $priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_CURRENCY],
                        static::PARAM_STORE => $priceData[MerchantCombinedProductOfferPriceExtractorStep::KEY_STORE],
                    ]),
            );
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function getProductOfferPrices(DataSetInterface $dataSet): array
    {
        return $dataSet[CombinedProductOfferDataSetInterface::DATA_PRODUCT_OFFER_PRICES] ?? [];
    }

    /**
     * @return array<string, int>
     */
    protected function getStoreIdsIndexedByName(DataSetInterface $dataSet): array
    {
        return $dataSet[AddStoresStep::KEY_STORES] ?? [];
    }

    /**
     * @return array<string, int>
     */
    protected function getPriceTypeIdsIndexedByName(DataSetInterface $dataSet): array
    {
        return $dataSet[CombinedProductOfferDataSetInterface::DATA_PRICE_TYPE_IDS_INDEXED_BY_NAME] ?? [];
    }

    /**
     * @return array<string, int>
     */
    protected function getCurrencyIdsIndexedByCode(DataSetInterface $dataSet): array
    {
        return $dataSet[CombinedProductOfferDataSetInterface::DATA_CURRENCY_IDS_INDEXED_BY_CODE] ?? [];
    }

    /**
     * @return array<string, array<string>>
     */
    protected function getCurrencyNamesIndexedByStoreName(DataSetInterface $dataSet): array
    {
        return $dataSet[CombinedProductOfferDataSetInterface::DATA_CURRENCY_NAMES_INDEXED_BY_STORE_NAME] ?? [];
    }
}
