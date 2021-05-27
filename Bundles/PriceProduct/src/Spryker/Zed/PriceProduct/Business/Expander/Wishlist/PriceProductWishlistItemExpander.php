<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Expander\Wishlist;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class PriceProductWishlistItemExpander implements PriceProductWishlistItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface
     */
    protected $priceProductConcreteReader;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $priceProductConfig;

    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductConcreteReaderInterface $priceProductConcreteReader
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $priceProductConfig
     * @param \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        PriceProductConcreteReaderInterface $priceProductConcreteReader,
        PriceProductConfig $priceProductConfig,
        PriceProductToStoreFacadeInterface $storeFacade
    ) {
        $this->priceProductConcreteReader = $priceProductConcreteReader;
        $this->priceProductConfig = $priceProductConfig;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())->setType(
                    $this->priceProductConfig->getPriceDimensionDefault()
                )
            )
            ->setIdStore($this->storeFacade->getCurrentStore()->getIdStore());

        /** @var string $sku */
        $sku = $wishlistItemTransfer->requireSku()->getSku();
        $priceProductTransfers = $this->priceProductConcreteReader->findProductConcretePricesBySkuAndCriteria(
            $sku,
            $priceProductCriteriaTransfer
        );

        $priceProductTransfers = new ArrayObject($priceProductTransfers);

        return $wishlistItemTransfer->setPrices($priceProductTransfers);
    }
}
