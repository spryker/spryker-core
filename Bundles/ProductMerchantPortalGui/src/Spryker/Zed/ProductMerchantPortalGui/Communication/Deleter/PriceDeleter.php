<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Deleter;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface;

class PriceDeleter implements PriceDeleterInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_ERROR_PRICE_PRODUCT_HAS_VOLUME_PRICES = 'Price product with quantity 1 cannot be deleted when there are volume prices for the same store and currency';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     * @var string
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

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
        $this->priceProductFacade = $priceProductFacade;
        $this->priceProductVolumeService = $priceProductVolumeService;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param int $volumeQuantity
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function deletePrices(array $priceProductTransfers, int $volumeQuantity): ValidationResponseTransfer
    {
        $validationResponseTransfer = (new ValidationResponseTransfer())
            ->setIsSuccess(true);

        if ($volumeQuantity === 1) {
            foreach ($priceProductTransfers as $priceProductTransfer) {
                $validationResponseTransfer = $this->validatePriceProduct(
                    $priceProductTransfer,
                    $validationResponseTransfer,
                );

                if (!$validationResponseTransfer->getIsSuccess()) {
                    return $validationResponseTransfer;
                }

                $this->priceProductFacade
                    ->removePriceProductDefaultForPriceProduct($priceProductTransfer);
            }

            return $validationResponseTransfer;
        }

        $this->deleteVolumePrice($priceProductTransfers, $volumeQuantity);

        return $validationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\ValidationResponseTransfer $validationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    protected function validatePriceProduct(
        PriceProductTransfer $priceProductTransfer,
        ValidationResponseTransfer $validationResponseTransfer
    ): ValidationResponseTransfer {
        if ($this->priceProductVolumeService->hasVolumePrices($priceProductTransfer)) {
            $validationResponseTransfer
                ->setIsSuccess(false)
                ->addValidationError(
                    (new ValidationErrorTransfer())
                        ->setMessage(static::MESSAGE_ERROR_PRICE_PRODUCT_HAS_VOLUME_PRICES),
                );
        }

        return $validationResponseTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param int $volumeQuantity
     *
     * @return void
     */
    protected function deleteVolumePrice(array $priceProductTransfers, int $volumeQuantity): void
    {
        $defaultPriceProductTransfer = $this->findDefaultPriceProduct($priceProductTransfers);

        if (!$defaultPriceProductTransfer) {
            return;
        }

        $volumePriceProductTransferToDelete = (new PriceProductTransfer())
            ->setVolumeQuantity($volumeQuantity);

        $defaultPriceProductTransfer = $this->priceProductVolumeService->deleteVolumePrice(
            $defaultPriceProductTransfer,
            $volumePriceProductTransferToDelete,
        );

        $this->priceProductFacade
            ->persistPriceProductStore($defaultPriceProductTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function findDefaultPriceProduct(array $priceProductTransfers): ?PriceProductTransfer
    {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceTypeName = $priceProductTransfer
                ->getPriceTypeOrFail()
                ->getNameOrFail();

            if ($priceTypeName === static::PRICE_TYPE_DEFAULT) {
                return $priceProductTransfer;
            }
        }

        return null;
    }
}
