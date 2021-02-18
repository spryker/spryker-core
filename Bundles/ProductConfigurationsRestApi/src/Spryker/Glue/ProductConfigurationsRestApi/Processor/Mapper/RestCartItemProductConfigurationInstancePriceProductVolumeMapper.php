<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer;
use Generated\Shared\Transfer\RestCurrencyTransfer;
use Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer;
use Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToProductConfigurationServiceInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToUtilEncodingServiceInterface;

class RestCartItemProductConfigurationInstancePriceProductVolumeMapper implements RestCartItemProductConfigurationInstancePriceProductVolumeMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToProductConfigurationServiceInterface
     */
    protected $productConfigurationService;

    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToProductConfigurationServiceInterface $productConfigurationService
     * @param \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductConfigurationsRestApiToProductConfigurationServiceInterface $productConfigurationService,
        ProductConfigurationsRestApiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->productConfigurationService = $productConfigurationService;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function mapRestCartItemProductConfigurationInstanceAttributesToProductConfigurationInstanceTransfer(
        RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        foreach ($restCartItemProductConfigurationInstanceAttributesTransfer->getPrices() as $restProductConfigurationPriceAttributesTransfer) {
            if ($restProductConfigurationPriceAttributesTransfer->getVolumePrices()->count() === 0) {
                continue;
            }

            $productConfigurationInstanceTransfer = $this->mapRestProductConfigurationPriceAttributesVolumePricesToProductConfigurationInstanceTransfer(
                $restProductConfigurationPriceAttributesTransfer,
                $productConfigurationInstanceTransfer
            );
        }

        $priceProductTransfers = $this->fillUpPriceDimensionWithProductConfigurationInstanceHash(
            $productConfigurationInstanceTransfer->getPrices(),
            $this->productConfigurationService
                ->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer)
        );

        return $productConfigurationInstanceTransfer->setPrices($priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    protected function mapRestProductConfigurationPriceAttributesVolumePricesToProductConfigurationInstanceTransfer(
        RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        $extractedPriceProductTransfers = [];
        foreach ($productConfigurationInstanceTransfer->getPrices() as $priceProductTransfer) {
            if ($restProductConfigurationPriceAttributesTransfer->getPriceTypeName() !== $priceProductTransfer->getPriceTypeName()) {
                continue;
            }

            $extractedPriceProductTransfers[] = $this->extractVolumePrices(
                $priceProductTransfer,
                $restProductConfigurationPriceAttributesTransfer
            );
        }

        $extractedPriceProductTransfers = array_merge(...$extractedPriceProductTransfers);
        foreach ($extractedPriceProductTransfers as $priceProductTransfer) {
            $productConfigurationInstanceTransfer->addPrice($priceProductTransfer);
        }

        return $productConfigurationInstanceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function extractVolumePrices(
        PriceProductTransfer $priceProductTransfer,
        RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
    ): array {
        $extractedPrices = [];
        foreach ($restProductConfigurationPriceAttributesTransfer->getVolumePrices() as $restProductPriceVolumesAttributesTransfer) {
            $priceProductTransferForMapping = (new PriceProductTransfer())
                ->fromArray($priceProductTransfer->toArray(), true);

            $extractedPrices[] = $this->mapVolumePriceDataToPriceProductTransfer(
                $priceProductTransferForMapping,
                $restProductPriceVolumesAttributesTransfer,
                $restProductConfigurationPriceAttributesTransfer->getCurrencyOrFail()
            );
        }

        return $extractedPrices;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer
     * @param \Generated\Shared\Transfer\RestCurrencyTransfer $restCurrencyTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function mapVolumePriceDataToPriceProductTransfer(
        PriceProductTransfer $priceProductTransfer,
        RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer,
        RestCurrencyTransfer $restCurrencyTransfer
    ): PriceProductTransfer {
        $groupKey = sprintf('%s-%s', $priceProductTransfer->getGroupKey(), $restProductPriceVolumesAttributesTransfer->getQuantity());
        $moneyValueTransfer = $priceProductTransfer->getMoneyValue() ?? new MoneyValueTransfer();
        $moneyValueTransfer
            ->fromArray($restProductPriceVolumesAttributesTransfer->toArray(), true)
            ->setPriceData($this->utilEncodingService->encodeJson($restProductPriceVolumesAttributesTransfer->toArray()))
            ->setCurrency((new CurrencyTransfer())->fromArray($restCurrencyTransfer->toArray(), true));

        return $priceProductTransfer
            ->setVolumeQuantity($restProductPriceVolumesAttributesTransfer->getQuantity())
            ->setGroupKey($groupKey)
            ->setIsMergeable(false)
            ->setMoneyValue($moneyValueTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[]|\ArrayObject $priceProductTransfers
     * @param string $productConfigurationInstanceHash
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]|\ArrayObject
     */
    protected function fillUpPriceDimensionWithProductConfigurationInstanceHash(
        ArrayObject $priceProductTransfers,
        string $productConfigurationInstanceHash
    ): ArrayObject {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductDimensionTransfer = $priceProductTransfer->getPriceDimension() ?? new PriceProductDimensionTransfer();
            $priceProductDimensionTransfer->setProductConfigurationInstanceHash($productConfigurationInstanceHash);

            $priceProductTransfer->setPriceDimension($priceProductDimensionTransfer);
        }

        return $priceProductTransfers;
    }
}
