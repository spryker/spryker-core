<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAfterExecuteInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferStoreEvents;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;

class MerchantProductOfferStoreWriterStep implements DataImportStepInterface, DataImportStepAfterExecuteInterface
{
    /**
     * @var \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    protected $entityEventTransfers = [];

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     */
    public function __construct(DataImportToEventFacadeInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

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

        $this->addPublishEvent($productOfferStoreEntity);
    }

    /**
     * @return void
     */
    public function afterExecute(): void
    {
        foreach ($this->entityEventTransfers as $entityEventTransfer) {
            $this->eventFacade->trigger(MerchantProductOfferStoreEvents::MERCHANT_PRODUCT_OFFER_STORE_KEY_PUBLISH, $entityEventTransfer);
        }

        $this->entityEventTransfers = [];
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferStore $productOfferStoreEntity
     *
     * @return void
     */
    protected function addPublishEvent(SpyProductOfferStore $productOfferStoreEntity): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($productOfferStoreEntity->getFkProductOffer());
        $eventEntityTransfer->setAdditionalValues([
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferStoreEntity->getSpyProductOffer()->getProductOfferReference(),
            SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferStoreEntity->getSpyProductOffer()->getConcreteSku(),
        ]);

        $this->entityEventTransfers[] = $eventEntityTransfer;
    }
}
