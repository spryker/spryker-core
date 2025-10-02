<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\Trait;

use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;

trait ProductOfferGetterTrait
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PRODUCT_OFFER_NOT_FOUND = 'Product offer not found';

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function getProductOfferFromDataSet(DataSetInterface $dataSet): SpyProductOffer
    {
        $productOfferEntity = $dataSet[CombinedProductOfferDataSetInterface::DATA_PRODUCT_OFFER_ENTITY] ?? null;

        if (!$productOfferEntity) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_PRODUCT_OFFER_NOT_FOUND),
            );
        }

        return $productOfferEntity;
    }
}
