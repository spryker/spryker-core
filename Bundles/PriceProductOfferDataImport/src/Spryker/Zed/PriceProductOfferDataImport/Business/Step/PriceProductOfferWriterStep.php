<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer;
use Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOfferQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\PriceProductOffer\Dependency\PriceProductOfferEvents;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Dependency\PriceProductOfferDataImportEvents;

class PriceProductOfferWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @var array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    protected $productEventTransfers = [];

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
        /** @var \Orm\Zed\PriceProductOffer\Persistence\SpyPriceProductOffer|null $priceProductOfferEntity */
        $priceProductOfferEntity = SpyPriceProductOfferQuery::create()
            ->filterByFkProductOffer($dataSet[PriceProductOfferDataSetInterface::FK_PRODUCT_OFFER])
            ->useSpyPriceProductStoreQuery()
                ->filterByFkStore($dataSet[PriceProductOfferDataSetInterface::FK_STORE])
                ->filterByFkCurrency($dataSet[PriceProductOfferDataSetInterface::FK_CURRENCY])
                ->filterByFkPriceProduct($dataSet[PriceProductOfferDataSetInterface::FK_PRICE_PRODUCT])
            ->endUse()
            ->findOne();

        if ($priceProductOfferEntity === null) {
            $priceProductOfferEntity = new SpyPriceProductOffer();
        }

        $priceProductOfferEntity->setFkProductOffer($dataSet[PriceProductOfferDataSetInterface::FK_PRODUCT_OFFER]);
        $priceProductOfferEntity->setFkPriceProductStore($dataSet[PriceProductOfferDataSetInterface::FK_PRICE_PRODUCT_STORE]);
        $priceProductOfferEntity->save();

        $this->addPublishEvents(PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH, (int)$priceProductOfferEntity->getIdPriceProductOffer());
        $this->addProductUpdateEvent((int)$dataSet[PriceProductOfferDataSetInterface::ID_PRODUCT_CONCRETE]);
    }

    /**
     * @return void
     */
    public function afterExecute(): void
    {
        parent::afterExecute();

        $this->eventFacade->triggerBulk(
            PriceProductOfferDataImportEvents::PRODUCT_CONCRETE_UPDATE,
            $this->productEventTransfers,
        );

        $this->productEventTransfers = [];
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    protected function addProductUpdateEvent(int $idProductConcrete): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($idProductConcrete);

        $this->productEventTransfers[] = $eventEntityTransfer;
    }
}
