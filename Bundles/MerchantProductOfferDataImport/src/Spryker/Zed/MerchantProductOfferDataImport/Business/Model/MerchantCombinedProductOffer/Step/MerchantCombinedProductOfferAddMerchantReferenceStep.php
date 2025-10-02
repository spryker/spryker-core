<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step;

use Generated\Shared\Transfer\DataImporterConfigurationContextTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;

class MerchantCombinedProductOfferAddMerchantReferenceStep implements DataImportStepInterface
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
    protected const ERROR_MESSAGE_MERCHANT_NOT_FOUND = 'Merchant not found.';

    /**
     * @var array<int, string>
     */
    protected array $merchantReferenceCache = [];

    public function execute(DataSetInterface $dataSet): void
    {
        $context = $this->getContext($dataSet);

        $dataSet[CombinedProductOfferDataSetInterface::MERCHANT_REFERENCE] = $this->getMerchantReferenceById(
            $context->getIdMerchantOrFail(),
        );
    }

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function getMerchantReferenceById(int $idMerchant): string
    {
        if (isset($this->merchantReferenceCache[$idMerchant])) {
            return $this->merchantReferenceCache[$idMerchant];
        }

        $merchantQuery = SpyMerchantQuery::create()
            ->filterByIdMerchant($idMerchant)
            ->select(SpyMerchantTableMap::COL_MERCHANT_REFERENCE);

        $merchantReference = $merchantQuery->findOne();

        if (!$merchantReference) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_MERCHANT_NOT_FOUND),
            );
        }

        $this->merchantReferenceCache[$idMerchant] = $merchantReference;

        return $this->merchantReferenceCache[$idMerchant];
    }

    protected function getContext(DataSetInterface $dataSet): DataImporterConfigurationContextTransfer
    {
        return $dataSet[static::KEY_CONTEXT];
    }
}
