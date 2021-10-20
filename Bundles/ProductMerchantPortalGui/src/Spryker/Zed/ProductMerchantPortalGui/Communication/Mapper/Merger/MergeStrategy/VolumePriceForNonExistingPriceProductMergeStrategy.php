<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface;

class VolumePriceForNonExistingPriceProductMergeStrategy extends AbstractPriceProductMergeStrategy
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface
     */
    protected $priceProductVolumeService;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService
     */
    public function __construct(ProductMerchantPortalGuiToPriceProductVolumeServiceInterface $priceProductVolumeService)
    {
        $this->priceProductVolumeService = $priceProductVolumeService;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return bool
     */
    public function isApplicable(
        PriceProductTransfer $newPriceProductTransfer,
        ArrayObject $priceProductTransfers
    ): bool {
        $isVolumePriceProduct = $this->isVolumePriceProduct($newPriceProductTransfer);
        $isPriceProductInCollection = $this->isPriceProductInCollection(
            $newPriceProductTransfer,
            $priceProductTransfers,
        );

        return $isVolumePriceProduct && !$isPriceProductInCollection;
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
        $priceProductTransfer = (new PriceProductTransfer())
            ->fromArray($newPriceProductTransfer->toArray());
        $priceProductTransfer
            ->setVolumeQuantity(null)
            ->getMoneyValueOrFail()
            ->setNetAmount(null)
            ->setGrossAmount(null);

        $priceProductTransfer = $this->priceProductVolumeService
            ->addVolumePrice(
                $priceProductTransfer,
                $newPriceProductTransfer,
            );

        $priceProductTransfers->append($priceProductTransfer);

        return $priceProductTransfers;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
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
