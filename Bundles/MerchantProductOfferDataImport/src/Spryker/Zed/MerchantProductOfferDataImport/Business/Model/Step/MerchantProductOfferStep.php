<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAfterExecuteInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\MerchantProductOffer\Dependency\MerchantProductOfferEvents;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;

class MerchantProductOfferStep implements DataImportStepInterface, DataImportStepAfterExecuteInterface
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
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (empty($dataSet[MerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE])) {
            throw new EntityNotFoundException('Product offer reference is a required field');
        }

        $spyProductOffer = SpyProductOfferQuery::create()
            ->filterByProductOfferReference($dataSet[MerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE])
            ->findOneOrCreate();
        $spyProductOffer->setFkMerchant($dataSet[MerchantProductOfferDataSetInterface::FK_MERCHANT]);
        $spyProductOffer->setConcreteSku($dataSet[MerchantProductOfferDataSetInterface::CONCRETE_SKU]);
        $spyProductOffer->save();

        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($spyProductOffer->getIdProductOffer());
        $eventEntityTransfer->setAdditionalValues([
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $spyProductOffer->getProductOfferReference(),
            SpyProductOfferTableMap::COL_CONCRETE_SKU => $spyProductOffer->getConcreteSku(),
        ]);

        $this->addPublishEvents(MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_KEY_PUBLISH, $eventEntityTransfer);
    }

    /**
     * @return void
     */
    public function afterExecute()
    {
        foreach ($this->entityEventTransfers as $entityEventTransfer) {
            $this->eventFacade->trigger(MerchantProductOfferEvents::MERCHANT_PRODUCT_OFFER_KEY_PUBLISH, $entityEventTransfer);
        }

        $this->entityEventTransfers = [];
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\EventEntityTransfer $entityEventTransfer
     *
     * @return void
     */
    public function addPublishEvents($eventName, $entityEventTransfer)
    {
        $this->entityEventTransfers[] = $entityEventTransfer;
    }
}
