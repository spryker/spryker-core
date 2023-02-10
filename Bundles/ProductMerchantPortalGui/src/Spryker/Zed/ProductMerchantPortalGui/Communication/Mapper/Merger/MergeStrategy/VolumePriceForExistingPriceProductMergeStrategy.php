<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductServiceInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface;

class VolumePriceForExistingPriceProductMergeStrategy extends AbstractPriceProductMergeStrategy
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface
     */
    protected ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductServiceInterface $priceProductService
     */
    public function __construct(
        ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService,
        ProductMerchantPortalGuiToPriceProductServiceInterface $priceProductService
    ) {
        $this->priceProductVolumeService = $priceProductVolumeService;

        parent::__construct($priceProductService);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return bool
     */
    public function isApplicable(
        PriceProductTransfer $newPriceProductTransfer,
        ArrayObject $priceProductTransfers
    ): bool {
        return $this->isVolumePriceProduct($newPriceProductTransfer)
            && $this->isPriceProductInCollection($newPriceProductTransfer, $priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function merge(
        PriceProductTransfer $newPriceProductTransfer,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($this->isSamePriceProduct($priceProductTransfer, $newPriceProductTransfer)) {
                $this->priceProductVolumeService->addVolumePrice($priceProductTransfer, $newPriceProductTransfer);

                return $priceProductTransfers;
            }
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return bool
     */
    protected function isPriceProductInCollection(
        PriceProductTransfer $newPriceProductTransfer,
        ArrayObject $priceProductTransfers
    ): bool {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($this->isSamePriceProduct($priceProductTransfer, $newPriceProductTransfer)) {
                return true;
            }
        }

        return false;
    }
}
