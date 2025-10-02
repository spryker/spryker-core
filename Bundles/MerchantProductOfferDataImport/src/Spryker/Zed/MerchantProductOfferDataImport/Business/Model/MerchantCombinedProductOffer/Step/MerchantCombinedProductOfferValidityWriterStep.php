<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step;

use Exception;
use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\ProductOfferValidity\Persistence\Map\SpyProductOfferValidityTableMap;
use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidity;
use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidityQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\Trait\ProductOfferGetterTrait;

class MerchantCombinedProductOfferValidityWriterStep implements DataImportStepInterface
{
    use ProductOfferGetterTrait;

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_START_DATE = 'Invalid date format of validity start date.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_END_DATE = 'Invalid date format of validity end date.';

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     */
    public function __construct(protected DataImportToEventFacadeInterface $eventFacade)
    {
    }

    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferEntity = $this->getProductOfferFromDataSet($dataSet);

        $this->persistProductOfferValidity($dataSet, $productOfferEntity);
    }

    protected function persistProductOfferValidity(DataSetInterface $dataSet, SpyProductOffer $productOfferEntity): void
    {
        /** @var \Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidityQuery $spyProductOfferValidityQuery */
        $spyProductOfferValidityQuery = SpyProductOfferValidityQuery::create();

        $productOfferValidityEntity = $spyProductOfferValidityQuery
            ->filterByFkProductOffer($productOfferEntity->getIdProductOffer())
            ->findOneOrCreate();

        $this->setValidFrom($productOfferValidityEntity, $dataSet);
        $this->setValidTo($productOfferValidityEntity, $dataSet);

        if (
            $productOfferValidityEntity->isColumnModified(SpyProductOfferValidityTableMap::COL_VALID_FROM)
            || $productOfferValidityEntity->isColumnModified(SpyProductOfferValidityTableMap::COL_VALID_TO)
        ) {
            $productOfferValidityEntity->save();
        }
    }

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function setValidFrom(SpyProductOfferValidity $productOfferValidityEntity, DataSetInterface $dataSet): void
    {
        if (!isset($dataSet[CombinedProductOfferDataSetInterface::VALID_FROM])) {
            return;
        }

        try {
            $productOfferValidityEntity->setValidFrom($dataSet[CombinedProductOfferDataSetInterface::VALID_FROM]);
        } catch (Exception) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_INVALID_START_DATE),
            );
        }
    }

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function setValidTo(SpyProductOfferValidity $productOfferValidityEntity, DataSetInterface $dataSet): void
    {
        if (!isset($dataSet[CombinedProductOfferDataSetInterface::VALID_TO])) {
            return;
        }

        try {
            $productOfferValidityEntity->setValidTo($dataSet[CombinedProductOfferDataSetInterface::VALID_TO]);
        } catch (Exception) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_INVALID_END_DATE),
            );
        }
    }
}
