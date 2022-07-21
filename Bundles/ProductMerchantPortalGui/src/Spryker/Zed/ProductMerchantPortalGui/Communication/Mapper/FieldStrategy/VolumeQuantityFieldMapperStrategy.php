<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface;

class VolumeQuantityFieldMapperStrategy extends AbstractFieldMapperStrategy
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface
     */
    protected $priceProductVolumeService;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService
     */
    public function __construct(
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService
    ) {
        parent::__construct($priceProductFacade);

        $this->priceProductVolumeService = $priceProductVolumeService;
    }

    /**
     * @param string $dataField
     *
     * @return bool
     */
    public function isApplicable(string $dataField): bool
    {
        return $dataField === PriceProductTransfer::VOLUME_QUANTITY;
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
        $dataField = key($data);
        $newVolumeQuantity = (int)$data[$dataField];
        $priceProductTransfer = $this->findDefaultPriceProduct($priceProductTransfers);

        if (!$priceProductTransfer) {
            return $priceProductTransfers;
        }

        if ($volumeQuantity > 1 && $newVolumeQuantity === 1) {
            $this->moveVolumePriceToPriceProductTransfer($volumeQuantity, $priceProductTransfer);

            return $priceProductTransfers;
        }

        if ($volumeQuantity > 1 && ($newVolumeQuantity > 1 || $newVolumeQuantity === 0)) {
            $this->replaceVolumePrice($priceProductTransfer, $volumeQuantity, $newVolumeQuantity);

            return $priceProductTransfers;
        }

        if ($volumeQuantity === 1 && $newVolumeQuantity > 1) {
            $this->movePriceProductToVolumePrice($newVolumeQuantity, $priceProductTransfer);
        }

        if ($volumeQuantity === 1 && $newVolumeQuantity === 0) {
            $priceProductTransfer->setVolumeQuantity($newVolumeQuantity);
        }

        return $priceProductTransfers;
    }

    /**
     * @param int $volumeQuantity
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function moveVolumePriceToPriceProductTransfer(
        int $volumeQuantity,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $volumePriceProductTransfer = $this->extractVolumePriceProduct($volumeQuantity, $priceProductTransfer);

        if (!$volumePriceProductTransfer) {
            return $priceProductTransfer;
        }

        $priceProductTransfer
            ->getMoneyValueOrFail()
            ->setNetAmount($volumePriceProductTransfer->getMoneyValueOrFail()->getNetAmount())
            ->setGrossAmount($volumePriceProductTransfer->getMoneyValueOrFail()->getGrossAmount());

        $this->priceProductVolumeService
            ->deleteVolumePrice($priceProductTransfer, $volumePriceProductTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param int $volumeQuantity
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function extractVolumePriceProduct(
        int $volumeQuantity,
        PriceProductTransfer $priceProductTransfer
    ): ?PriceProductTransfer {
        $volumePriceProductTransfer = (new PriceProductTransfer())
            ->setVolumeQuantity($volumeQuantity);

        return $this->priceProductVolumeService
            ->extractVolumePrice($priceProductTransfer, $volumePriceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int $volumeQuantity
     * @param int $newVolumeQuantity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function replaceVolumePrice(
        PriceProductTransfer $priceProductTransfer,
        int $volumeQuantity,
        int $newVolumeQuantity
    ): PriceProductTransfer {
        $newVolumePriceProductTransfer = (new PriceProductTransfer())
            ->setVolumeQuantity($newVolumeQuantity)
            ->setMoneyValue(new MoneyValueTransfer());

        return $this->priceProductVolumeService->replaceVolumePrice(
            $priceProductTransfer,
            (new PriceProductTransfer())->setVolumeQuantity($volumeQuantity),
            $newVolumePriceProductTransfer,
        );
    }

    /**
     * @param int $volumeQuantity
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function movePriceProductToVolumePrice(
        int $volumeQuantity,
        PriceProductTransfer $priceProductTransfer
    ): ?PriceProductTransfer {
        $newPriceProductTransfer = (new PriceProductTransfer())
            ->fromArray($priceProductTransfer->toArray());
        $newPriceProductTransfer
            ->setVolumeQuantity($volumeQuantity);

        $priceProductTransfer
            ->setVolumeQuantity(null)
            ->getMoneyValueOrFail()
            ->setNetAmount(null)
            ->setGrossAmount(null);

        return $this->priceProductVolumeService->addVolumePrice($priceProductTransfer, $newPriceProductTransfer);
    }
}
