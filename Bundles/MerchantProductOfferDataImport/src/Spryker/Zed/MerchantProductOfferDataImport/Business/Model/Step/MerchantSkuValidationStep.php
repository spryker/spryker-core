<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;

class MerchantSkuValidationStep implements DataImportStepInterface
{
    protected const MERCHANT_SKU = MerchantProductOfferDataSetInterface::MERCHANT_SKU;

    protected const ID_MERCHANT = MerchantProductOfferDataSetInterface::ID_MERCHANT;

    protected const MERCHANT_REFERENCE = MerchantProductOfferDataSetInterface::MERCHANT_REFERENCE;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantSku = $dataSet[static::MERCHANT_SKU];

        if (!$merchantSku) {
            return;
        }

        $merchantReference = $dataSet[static::MERCHANT_REFERENCE];

        /** @var \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery */
        $productOfferQuery = SpyProductOfferQuery::create();
        $productOfferQuery->filterByMerchantSku($merchantSku)
            ->filterByMerchantReference($merchantReference)
            ->filterByProductOfferReference($dataSet[MerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE], Criteria::ALT_NOT_EQUAL);

        if ($productOfferQuery->count() > 0) {
            throw new EntityNotFoundException(sprintf('Product with merchant sku "%s" and merchant reference "%s" should be unique.', $merchantSku, $merchantReference));
        }
    }
}
