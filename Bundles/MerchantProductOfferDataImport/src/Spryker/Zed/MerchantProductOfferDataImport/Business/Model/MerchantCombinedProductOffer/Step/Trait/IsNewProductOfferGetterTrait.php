<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\Trait;

use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;

trait IsNewProductOfferGetterTrait
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CANNOT_DETERMINE_IS_NEW_PRODUCT_OFFER = 'Cannot determine if the product offer is new.';

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function getIsNewProductOffer(DataSetInterface $dataSet): bool
    {
        $isNewProductOffer = $dataSet[CombinedProductOfferDataSetInterface::DATA_IS_NEW_PRODUCT_OFFER] ?? null;

        if ($isNewProductOffer === null) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_CANNOT_DETERMINE_IS_NEW_PRODUCT_OFFER),
            );
        }

        return $isNewProductOffer;
    }
}
