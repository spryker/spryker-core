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
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAfterExecuteInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\Trait\ProductGetterTrait;
use Spryker\Zed\MerchantProductOfferDataImport\Dependency\MerchantProductOfferDataImportEvents;

class MerchantCombinedProductOfferWriterStep extends PublishAwareStep implements DataImportStepInterface, DataImportStepAfterExecuteInterface
{
    use ProductGetterTrait;

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const DEFAULT_APPROVAL_STATUS = 'waiting_for_approval';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MISSING_REQUIRED_FIELD = 'The required field "%field%" is missing.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PRODUCT_OFFER_SKU_CHANGED = 'The SKU cannot be changed for existing product offers.';

    /**
     * @var string
     */
    protected const PARAM_FIELD = '%field%';

    /**
     * @var array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    protected array $productOfferEventTransfers = [];

    public function __construct(protected DataImportToEventFacadeInterface $eventFacade)
    {
    }

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$dataSet[CombinedProductOfferDataSetInterface::OFFER_REFERENCE]) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_MISSING_REQUIRED_FIELD)
                    ->setParameters([static::PARAM_FIELD => CombinedProductOfferDataSetInterface::OFFER_REFERENCE]),
            );
        }

        $dataSet[CombinedProductOfferDataSetInterface::DATA_PRODUCT_OFFER_ENTITY] = $this->persistProductOffer($dataSet);
    }

    public function afterExecute(): void
    {
        parent::afterExecute();

        $this->eventFacade->triggerBulk(
            MerchantProductOfferDataImportEvents::PRODUCT_OFFER_PUBLISH,
            $this->productOfferEventTransfers,
        );
        $this->productOfferEventTransfers = [];
    }

    protected function persistProductOffer(DataSetInterface $dataSet): SpyProductOffer
    {
        $productEntity = $this->getProductFromDataSet($dataSet);

        /** @var \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery */
        $productOfferQuery = SpyProductOfferQuery::create()
            ->filterByProductOfferReference($dataSet[CombinedProductOfferDataSetInterface::OFFER_REFERENCE]);

        $productOfferEntity = $productOfferQuery->findOneOrCreate();

        $this->validateDataSet($dataSet, $productOfferEntity);

        $dataSet[CombinedProductOfferDataSetInterface::DATA_IS_NEW_PRODUCT_OFFER] = $productOfferEntity->isNew();

        $productOfferEntity->setConcreteSku($dataSet[CombinedProductOfferDataSetInterface::CONCRETE_SKU]);
        $productOfferEntity->setMerchantReference($dataSet[CombinedProductOfferDataSetInterface::MERCHANT_REFERENCE]);

        if ($productOfferEntity->isNew()) {
            $productOfferEntity->setApprovalStatus(static::DEFAULT_APPROVAL_STATUS);
        }

        if (isset($dataSet[CombinedProductOfferDataSetInterface::IS_ACTIVE])) {
            $productOfferEntity->setIsActive($this->getIsActive($dataSet));
        }

        if (isset($dataSet[CombinedProductOfferDataSetInterface::MERCHANT_SKU])) {
            $productOfferEntity->setMerchantSku($dataSet[CombinedProductOfferDataSetInterface::MERCHANT_SKU]);
        }

        if ($productOfferEntity->isNew() || $productOfferEntity->isModified()) {
            $productOfferEntity->save();

            $this->addProductOfferPublishEvent($productOfferEntity);
            $this->addPublishEvents(MerchantProductOfferDataImportEvents::PRODUCT_CONCRETE_UPDATE, $productEntity->getIdProduct());
        }

        return $productOfferEntity;
    }

    protected function addProductOfferPublishEvent(SpyProductOffer $productOfferEntity): void
    {
        $this->productOfferEventTransfers[] = (new EventEntityTransfer())
            ->setId($productOfferEntity->getIdProductOffer())
            ->setAdditionalValues([
                SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE => $productOfferEntity->getProductOfferReference(),
                SpyProductOfferTableMap::COL_CONCRETE_SKU => $productOfferEntity->getConcreteSku(),
            ]);
    }

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function validateDataSet(DataSetInterface $dataSet, SpyProductOffer $productOfferEntity): void
    {
        if ($productOfferEntity->isNew() && !isset($dataSet[CombinedProductOfferDataSetInterface::IS_ACTIVE])) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_MISSING_REQUIRED_FIELD)
                    ->setParameters([
                        '%s1%' => CombinedProductOfferDataSetInterface::IS_ACTIVE,
                    ]),
            );
        }

        if (
            !$productOfferEntity->isNew()
            && $dataSet[CombinedProductOfferDataSetInterface::CONCRETE_SKU] !== $productOfferEntity->getConcreteSku()
        ) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_PRODUCT_OFFER_SKU_CHANGED),
            );
        }
    }

    protected function getIsActive(DataSetInterface $dataSet): bool
    {
        return filter_var($dataSet[CombinedProductOfferDataSetInterface::IS_ACTIVE], FILTER_VALIDATE_BOOLEAN);
    }
}
