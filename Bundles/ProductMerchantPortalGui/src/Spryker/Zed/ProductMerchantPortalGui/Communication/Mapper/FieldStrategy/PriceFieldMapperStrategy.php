<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Laminas\Filter\StringToUpper;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface;

class PriceFieldMapperStrategy extends AbstractFieldMapperStrategy
{
    /**
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_NET = 'net';

    /**
     * @var string
     */
    protected const SUFFIX_PRICE_TYPE_GROSS = 'gross';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DIMENSION_DEFAULT
     *
     * @var string
     */
    protected const PRICE_DIMENSION_TYPE_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface
     */
    protected $priceProductVolumeService;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService,
        ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
    ) {
        parent::__construct($priceProductFacade);

        $this->priceProductVolumeService = $priceProductVolumeService;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param string $dataField
     *
     * @return bool
     */
    public function isApplicable(string $dataField): bool
    {
        return $this->isPriceField($dataField);
    }

    /**
     * @param array<string, mixed> $data
     * @param int $volumeQuantity
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mapDataToPriceProductTransfers(
        array $data,
        int $volumeQuantity,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        $dataField = (string)key($data);
        $priceTypeName = $this->extractPriceType($dataField);

        $priceProductTransfer = $this->extractPriceProductByType($priceTypeName, $priceProductTransfers);

        if (!$priceProductTransfer) {
            /** @var \Generated\Shared\Transfer\PriceProductTransfer $firstPriceProductTransfer */
            $firstPriceProductTransfer = $priceProductTransfers[0];
            $priceProductTransfer = $this->createNewPriceProduct($priceTypeName, $firstPriceProductTransfer);

            $priceProductTransfers[] = $priceProductTransfer;
        }

        if ($this->isVolumePriceField($volumeQuantity)) {
            $priceProductTransferToReplace = (new PriceProductTransfer())->setMoneyValue(new MoneyValueTransfer());
            $priceProductTransferToReplace->setVolumeQuantity($volumeQuantity);
            $priceProductTransferToReplace = $this->priceProductVolumeService->extractVolumePrice(
                $priceProductTransfer,
                $priceProductTransferToReplace,
            );

            if (!$priceProductTransferToReplace) {
                $priceProductTransferToReplace = (new PriceProductTransfer())->setMoneyValue(new MoneyValueTransfer());
                $priceProductTransferToReplace->setVolumeQuantity($volumeQuantity);
            }

            $this->mapDataToMoneyValueTransfer($data, $priceProductTransferToReplace->getMoneyValueOrFail());
            $this->priceProductVolumeService->deleteVolumePrice(
                $priceProductTransfer,
                (new PriceProductTransfer())->setVolumeQuantity((int)$volumeQuantity),
            );

            $this->priceProductVolumeService->addVolumePrice($priceProductTransfer, $priceProductTransferToReplace);

            return $priceProductTransfers;
        }

        $this->mapDataToMoneyValueTransfer($data, $priceProductTransfer->getMoneyValueOrFail());

        return $priceProductTransfers;
    }

    /**
     * @param string $priceTypeName
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function extractPriceProductByType(
        string $priceTypeName,
        ArrayObject $priceProductTransfers
    ): ?PriceProductTransfer {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($priceTypeName === $priceProductTransfer->getPriceTypeOrFail()->getNameOrFail()) {
                return $priceProductTransfer;
            }
        }

        return null;
    }

    /**
     * @param string $fieldName
     *
     * @return bool
     */
    protected function isPriceField(string $fieldName): bool
    {
        $pattern = sprintf(
            '/(\[%sAmount]|\[%sAmount])$/',
            static::SUFFIX_PRICE_TYPE_GROSS,
            static::SUFFIX_PRICE_TYPE_NET,
        );

        preg_match($pattern, $fieldName, $matches);

        return (bool)$matches;
    }

    /**
     * @param string $fieldName
     *
     * @return string
     */
    protected function extractPriceType(string $fieldName): string
    {
        $priceType = (string)strstr($fieldName, '[', true);

        return (new StringToUpper())
            ->filter($priceType);
    }

    /**
     * @param array<mixed> $data
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapDataToMoneyValueTransfer(
        array $data,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer {
        $priceKey = (string)key($data);
        $priceValue = $this->convertDecimalToInteger($data[$priceKey]);

        if (strpos($priceKey, MoneyValueTransfer::NET_AMOUNT) !== false) {
            return $moneyValueTransfer->setNetAmount($priceValue);
        }

        if (strpos($priceKey, MoneyValueTransfer::GROSS_AMOUNT) !== false) {
            return $moneyValueTransfer->setGrossAmount($priceValue);
        }

        return $moneyValueTransfer;
    }

    /**
     * @param mixed $value
     *
     * @return int|null
     */
    protected function convertDecimalToInteger($value): ?int
    {
        if ($value === '' || $value === null) {
            return null;
        }

        return $this->moneyFacade->convertDecimalToInteger((float)$value);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $volumePriceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createEmptyPriceProduct(PriceProductTransfer $volumePriceProductTransfer): PriceProductTransfer
    {
        $priceProductTransfer = (new PriceProductTransfer())
            ->fromArray($volumePriceProductTransfer->toArray());

        $priceProductTransfer
            ->setVolumeQuantity(null)
            ->getMoneyValueOrFail()
            ->setNetAmount(null)
            ->setGrossAmount(null);

        return $priceProductTransfer;
    }

    /**
     * @param int $volumeQuantity
     *
     * @return bool
     */
    protected function isVolumePriceField(int $volumeQuantity): bool
    {
        return $volumeQuantity > 1;
    }
}
