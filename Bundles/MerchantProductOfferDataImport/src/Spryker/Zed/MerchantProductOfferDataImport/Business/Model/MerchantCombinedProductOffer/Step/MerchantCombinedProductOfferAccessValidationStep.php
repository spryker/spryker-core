<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step;

use Generated\Shared\Transfer\DataImporterConfigurationContextTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;

class MerchantCombinedProductOfferAccessValidationStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Zed\DataImport\Business\Model\DataImporter::KEY_CONTEXT
     *
     * @var string
     */
    protected const KEY_CONTEXT = 'CONTEXT';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MISSING_REQUIRED_FIELD = 'The required field "%field%" is missing.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_OFFER_OWNERSHIP = 'Offers can only be updated by the merchants who own them.';

    /**
     * @var string
     */
    protected const PARAM_FIELD = '%field%';

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $contextTransfer = $this->getContext($dataSet);
        $offerReference = $dataSet[CombinedProductOfferDataSetInterface::OFFER_REFERENCE];

        if (!$offerReference) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_MISSING_REQUIRED_FIELD)
                    ->setParameters([static::PARAM_FIELD => CombinedProductOfferDataSetInterface::OFFER_REFERENCE]),
            );
        }

        $this->validateMerchantAccess($offerReference, $contextTransfer->getIdMerchantOrFail());
    }

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function validateMerchantAccess(string $offerReference, int $idMerchant): void
    {
        $productOfferQuery = SpyProductOfferQuery::create()
            ->innerJoinWithMerchant()
            ->filterByProductOfferReference($offerReference)
            ->select([SpyMerchantTableMap::COL_ID_MERCHANT]);

        $productOfferMerchantId = $productOfferQuery->findOne();

        if (!$productOfferMerchantId) {
            return;
        }

        if ($productOfferMerchantId !== $idMerchant) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_MERCHANT_OFFER_OWNERSHIP),
            );
        }
    }

    protected function getContext(DataSetInterface $dataSet): DataImporterConfigurationContextTransfer
    {
        return $dataSet[static::KEY_CONTEXT];
    }
}
