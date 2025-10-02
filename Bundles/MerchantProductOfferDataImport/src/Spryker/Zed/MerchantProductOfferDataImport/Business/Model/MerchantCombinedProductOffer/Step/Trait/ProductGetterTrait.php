<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step\Trait;

use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;

trait ProductGetterTrait
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PRODUCT_NOT_FOUND = 'Product not found.';

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    protected function getProductFromDataSet(DataSetInterface $dataSet): SpyProduct
    {
        $productEntity = $dataSet[CombinedProductOfferDataSetInterface::DATA_PRODUCT_ENTITY] ?? null;

        if (!$productEntity) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_PRODUCT_NOT_FOUND),
            );
        }

        return $productEntity;
    }
}
