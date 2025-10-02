<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddStoresStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAfterExecuteInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\Trait\IsNewProductOfferGetterTrait;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\Trait\ProductOfferGetterTrait;
use Spryker\Zed\MerchantProductOfferDataImport\Dependency\MerchantProductOfferDataImportEvents;

class MerchantCombinedProductOfferStoreWriterStep extends PublishAwareStep implements DataImportStepInterface, DataImportStepAfterExecuteInterface
{
    use ProductOfferGetterTrait;
    use IsNewProductOfferGetterTrait;

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STORE_NOT_FOUND = 'Store "%store%" not found.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STORE_RELATIONS_REQUIRED = 'Store relations are required for new product offers.';

    /**
     * @var string
     */
    protected const PARAM_STORE = '%store%';

    /**
     * @var string
     *
     * @phpstan-var non-empty-string
     */
    protected const DELIMITER_STORES = ';';

    /**
     * @var array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    protected array $productOfferStoreEventTransfers = [];

    public function __construct(protected DataImportToEventFacadeInterface $eventFacade)
    {
    }

    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $productOfferEntity = $this->getProductOfferFromDataSet($dataSet);

        $this->persistProductOfferStores($productOfferEntity, $dataSet);
    }

    public function afterExecute(): void
    {
        $this->eventFacade->triggerBulk(
            MerchantProductOfferDataImportEvents::PRODUCT_OFFER_STORE_PUBLISH,
            $this->productOfferStoreEventTransfers,
        );

        $this->productOfferStoreEventTransfers = [];
    }

    protected function persistProductOfferStores(SpyProductOffer $productOfferEntity, DataSetInterface $dataSet): void
    {
        $storeNames = $this->getStoreNames($dataSet);

        foreach ($storeNames as $storeName) {
            $this->persistProductOfferStore($productOfferEntity, $dataSet, $storeName);
        }
    }

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function persistProductOfferStore(
        SpyProductOffer $productOfferEntity,
        DataSetInterface $dataSet,
        string $storeName
    ): void {
        $storeIdsIndexedByName = $this->getStoreIdsIndexedByName($dataSet);

        if (!isset($storeIdsIndexedByName[$storeName])) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_STORE_NOT_FOUND)
                    ->setParameters([static::PARAM_STORE => $storeName]),
            );
        }

        $idStore = $storeIdsIndexedByName[$storeName];

        $productOfferStoreEntity = SpyProductOfferStoreQuery::create()
            ->filterByFkStore($idStore)
            ->filterByFkProductOffer($productOfferEntity->getIdProductOffer())
            ->findOneOrCreate();

        if ($productOfferStoreEntity->isNew() || $productOfferStoreEntity->isModified()) {
            $productOfferStoreEntity->save();

            $this->addProductOfferStorePublishEvent($productOfferStoreEntity);
        }
    }

    /**
     * @return list<string>
     */
    protected function getStoreNames(DataSetInterface $dataSet): array
    {
        if (!isset($dataSet[CombinedProductOfferDataSetInterface::STORE_RELATIONS])) {
            return [];
        }

        $storeNames = explode(
            static::DELIMITER_STORES,
            $dataSet[CombinedProductOfferDataSetInterface::STORE_RELATIONS],
        );

        return array_filter(array_map(trim(...), $storeNames));
    }

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        $storeRelations = $dataSet[CombinedProductOfferDataSetInterface::STORE_RELATIONS] ?? null;

        if (!$storeRelations && $this->getIsNewProductOffer($dataSet)) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_STORE_RELATIONS_REQUIRED),
            );
        }
    }

    protected function addProductOfferStorePublishEvent(SpyProductOfferStore $productOfferStoreEntity): void
    {
        $eventEntityTransfer = (new EventEntityTransfer())
            ->setId($productOfferStoreEntity->getFkProductOffer())
            ->setAdditionalValues([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferStoreEntity->getSpyProductOffer()->getProductOfferReference(),
                SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferStoreEntity->getSpyProductOffer()->getConcreteSku(),
            ]);

        $this->productOfferStoreEventTransfers[] = $eventEntityTransfer;
    }

    /**
     * @return array<string, int>
     */
    protected function getStoreIdsIndexedByName(DataSetInterface $dataSet): array
    {
        return $dataSet[AddStoresStep::KEY_STORES] ?? [];
    }
}
