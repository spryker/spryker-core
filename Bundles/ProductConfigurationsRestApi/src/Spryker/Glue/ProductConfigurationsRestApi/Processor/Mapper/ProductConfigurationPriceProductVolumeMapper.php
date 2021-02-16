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

class ProductConfigurationPriceProductVolumeMapper implements ProductConfigurationPriceProductVolumeMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToProductConfigurationServiceInterface
     */
    protected $priceConfigurationService;

    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToProductConfigurationServiceInterface $priceConfigurationService
     * @param \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Service\ProductConfigurationsRestApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductConfigurationsRestApiToProductConfigurationServiceInterface $priceConfigurationService,
        ProductConfigurationsRestApiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->priceConfigurationService = $priceConfigurationService;
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
            if (!$restProductConfigurationPriceAttributesTransfer->getVolumePrices()) {
                continue;
            }

            $productConfigurationInstanceTransfer = $this->mapRestProductConfigurationPriceAttributesVolumePricesToProductConfigurationInstanceTransfer(
                $restProductConfigurationPriceAttributesTransfer,
                $productConfigurationInstanceTransfer
            );
        }

        $priceProductTransfers = $this->fillUpPriceDimensionWithProductConfigurationInstanceHash(
            $productConfigurationInstanceTransfer->getPrices(),
            $this->priceConfigurationService
                ->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer)
        );

        return $productConfigurationInstanceTransfer->setPrices($priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer
     */
    public function mapProductConfigurationInstanceTransferToRestCartItemProductConfigurationInstanceAttributesTransfer(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
    ): RestCartItemProductConfigurationInstanceAttributesTransfer {
        if ($productConfigurationInstanceTransfer->getPrices()->count() === 0) {
            return $restCartItemProductConfigurationInstanceAttributesTransfer;
        }

        $volumePriceProductTransfers = $this->extractVolumePriceProductTransfersFromProductConfigurationPrices($productConfigurationInstanceTransfer->getPrices());

        $restProductConfigurationPriceAttributesTransfers = [];
        foreach ($productConfigurationInstanceTransfer->getPrices() as $priceProductTransfer) {
            if ($priceProductTransfer->getVolumeQuantity() !== null) {
                continue;
            }

            $restProductConfigurationPriceAttributesTransferToMap = $this->extractRestProductConfigurationPriceAttributesTransferToMapVolumePrices(
                $priceProductTransfer,
                $restCartItemProductConfigurationInstanceAttributesTransfer->getPrices()
            );

            if (!$restProductConfigurationPriceAttributesTransferToMap) {
                continue;
            }

            $restProductConfigurationPriceAttributesTransfers[] = $this->mapVolumePriceProductTransfersToRestCartItemProductConfigurationInstanceAttributesTransfer(
                $volumePriceProductTransfers,
                $restProductConfigurationPriceAttributesTransferToMap,
                $priceProductTransfer
            );
        }

        return $restCartItemProductConfigurationInstanceAttributesTransfer->setPrices(new ArrayObject($restProductConfigurationPriceAttributesTransfers));
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
     * @return array
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

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $volumePriceProductTransfers
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer
     */
    protected function mapVolumePriceProductTransfersToRestCartItemProductConfigurationInstanceAttributesTransfer(
        array $volumePriceProductTransfers,
        RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer,
        PriceProductTransfer $priceProductTransfer
    ): RestProductConfigurationPriceAttributesTransfer {
        foreach ($volumePriceProductTransfers as $volumePriceProductTransfer) {
            if ($volumePriceProductTransfer->getPriceTypeName() !== $priceProductTransfer->getPriceTypeName()) {
                continue;
            }

            $restProductPriceVolumesAttributesTransfer = $this->mapPriceProductTransferToRestProductPriceVolumesAttributesTransfer(
                $priceProductTransfer,
                new RestProductPriceVolumesAttributesTransfer()
            );
            $restProductPriceVolumesAttributesTransfer->setQuantity($volumePriceProductTransfer->getVolumeQuantity());
            $restProductConfigurationPriceAttributesTransfer->addVolumePrice($restProductPriceVolumesAttributesTransfer);
        }

        return $restProductConfigurationPriceAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPriceVolumesAttributesTransfer
     */
    protected function mapPriceProductTransferToRestProductPriceVolumesAttributesTransfer(
        PriceProductTransfer $priceProductTransfer,
        RestProductPriceVolumesAttributesTransfer $restProductPriceVolumesAttributesTransfer
    ): RestProductPriceVolumesAttributesTransfer {
        $restProductPriceVolumesAttributesTransfer->fromArray($priceProductTransfer->getMoneyValueOrFail()->toArray(), true);
        $restProductPriceVolumesAttributesTransfer->setQuantity($priceProductTransfer->getVolumeQuantity());

        return $restProductPriceVolumesAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[]|\ArrayObject $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function extractVolumePriceProductTransfersFromProductConfigurationPrices(ArrayObject $priceProductTransfers): array
    {
        return array_filter($priceProductTransfers->getArrayCopy(), function (PriceProductTransfer $priceProductTransfer) {
            return $priceProductTransfer->getMoneyValueOrFail()->getPriceData();
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer[]|\ArrayObject $restProductConfigurationPriceAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer|null
     */
    protected function extractRestProductConfigurationPriceAttributesTransferToMapVolumePrices(
        PriceProductTransfer $priceProductTransfer,
        ArrayObject $restProductConfigurationPriceAttributesTransfers
    ): ?RestProductConfigurationPriceAttributesTransfer {
        foreach ($restProductConfigurationPriceAttributesTransfers as $restProductConfigurationPriceAttributesTransfer) {
            if (
                $this->isPriceProductTransferCorrespondsToRestProductConfigurationPriceAttributesTransfer(
                    $priceProductTransfer,
                    $restProductConfigurationPriceAttributesTransfer
                )
            ) {
                return $restProductConfigurationPriceAttributesTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
     *
     * @return bool
     */
    protected function isPriceProductTransferCorrespondsToRestProductConfigurationPriceAttributesTransfer(
        PriceProductTransfer $priceProductTransfer,
        RestProductConfigurationPriceAttributesTransfer $restProductConfigurationPriceAttributesTransfer
    ): bool {
        return $priceProductTransfer->getPriceTypeName() === $restProductConfigurationPriceAttributesTransfer->getPriceTypeName()
            && $priceProductTransfer->getMoneyValueOrFail()->getGrossAmount() === $restProductConfigurationPriceAttributesTransfer->getGrossAmount()
            && $priceProductTransfer->getMoneyValueOrFail()->getNetAmount() === $restProductConfigurationPriceAttributesTransfer->getNetAmount()
            && $priceProductTransfer->getMoneyValueOrFail()->getCurrencyOrFail()->getName() === $restProductConfigurationPriceAttributesTransfer->getCurrencyOrFail()->getName();
    }
}
