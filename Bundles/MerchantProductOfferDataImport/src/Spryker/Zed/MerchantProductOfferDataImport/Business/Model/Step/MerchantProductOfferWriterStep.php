<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAfterExecuteInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Dependency\MerchantProductOfferDataImportEvents;

class MerchantProductOfferWriterStep implements DataImportStepInterface, DataImportStepAfterExecuteInterface
{
    protected const PRODUCT_OFFER_REFERENCE = MerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE;

    protected const ID_MERCHANT = MerchantProductOfferDataSetInterface::ID_MERCHANT;

    protected const MERCHANT_REFERENCE = MerchantProductOfferDataSetInterface::MERCHANT_REFERENCE;

    protected const CONCRETE_SKU = MerchantProductOfferDataSetInterface::CONCRETE_SKU;

    protected const MERCHANT_SKU = MerchantProductOfferDataSetInterface::MERCHANT_SKU;

    protected const IS_ACTIVE = MerchantProductOfferDataSetInterface::IS_ACTIVE;

    protected const APPROVAL_STATUS = MerchantProductOfferDataSetInterface::APPROVAL_STATUS;

    /**
     * @uses \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::PRODUCT_OFFER_PUBLISH
     *
     * @var string
     */
    protected const PRODUCT_OFFER_PUBLISH = 'ProductOffer.product_offer.publish';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const DEFAULT_APPROVAL_STATUS = 'denied';

    /**
     * @var array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    protected $productOfferEventTransfers = [];

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
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (empty($dataSet[static::PRODUCT_OFFER_REFERENCE])) {
            throw new EntityNotFoundException('Product offer reference is a required field');
        }

        $productOfferEntity = SpyProductOfferQuery::create()
            ->filterByProductOfferReference($dataSet[static::PRODUCT_OFFER_REFERENCE])
            ->findOneOrCreate();
        $productOfferEntity->setMerchantReference($dataSet[static::MERCHANT_REFERENCE]);
        $productOfferEntity->setConcreteSku($dataSet[static::CONCRETE_SKU]);
        $productOfferEntity->setMerchantSku($dataSet[static::MERCHANT_SKU] ?: null);
        $productOfferEntity->setIsActive($dataSet[static::IS_ACTIVE]);
        $productOfferEntity->setApprovalStatus($dataSet[static::APPROVAL_STATUS] ?? static::DEFAULT_APPROVAL_STATUS);
        $productOfferEntity->save();

        $this->addPublishEvent($productOfferEntity);
        $this->addProductUpdateEvent($productOfferEntity->getConcreteSku());
    }

    /**
     * @return void
     */
    public function afterExecute(): void
    {
        $this->eventFacade->triggerBulk(static::PRODUCT_OFFER_PUBLISH, $this->productOfferEventTransfers);
        $this->productOfferEventTransfers = [];

        $this->eventFacade->triggerBulk(
            MerchantProductOfferDataImportEvents::PRODUCT_CONCRETE_UPDATE,
            $this->productEventTransfers,
        );

        $this->productEventTransfers = [];
    }

    /**
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOffer $productOfferEntity
     *
     * @return void
     */
    protected function addPublishEvent(SpyProductOffer $productOfferEntity): void
    {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($productOfferEntity->getIdProductOffer());
        $eventEntityTransfer->setAdditionalValues([
            SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferEntity->getProductOfferReference(),
            SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferEntity->getConcreteSku(),
        ]);

        $this->productOfferEventTransfers[] = $eventEntityTransfer;
    }

    /**
     * @param string $concreteProductSku
     *
     * @return void
     */
    protected function addProductUpdateEvent(string $concreteProductSku): void
    {
        $spyProduct = SpyProductQuery::create()
            ->filterBySku($concreteProductSku)
            ->findOne();

        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setId($spyProduct->getIdProduct());

        $this->productEventTransfers[] = $eventEntityTransfer;
    }
}
