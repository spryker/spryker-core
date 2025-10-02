<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException;

class MerchantCombinedProductOfferAddProductStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MISSING_REQUIRED_FIELD = 'The required field "%field%" is missing.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PRODUCT_NOT_FOUND = 'Product with SKU "%sku%" not found.';

    /**
     * @var string
     */
    protected const PARAM_FIELD = '%field%';

    /**
     * @var string
     */
    protected const PARAM_SKU = '%sku%';

    /**
     * @throws \Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Exception\MerchantCombinedProductOfferException
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (empty($dataSet[CombinedProductOfferDataSetInterface::CONCRETE_SKU])) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_MISSING_REQUIRED_FIELD)
                    ->setParameters([static::PARAM_FIELD => CombinedProductOfferDataSetInterface::CONCRETE_SKU]),
            );
        }

        /** @var string $concreteSku */
        $concreteSku = $dataSet[CombinedProductOfferDataSetInterface::CONCRETE_SKU];

        /** @var \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery */
        $productQuery = (SpyProductQuery::create())
            ->filterBySku($concreteSku);

        $productEntity = $productQuery->findOne();

        if (!$productEntity) {
            throw MerchantCombinedProductOfferException::createWithError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_PRODUCT_NOT_FOUND)
                    ->setParameters([static::PARAM_SKU => $concreteSku]),
            );
        }

        $dataSet[CombinedProductOfferDataSetInterface::DATA_PRODUCT_ENTITY] = $productEntity;
    }
}
