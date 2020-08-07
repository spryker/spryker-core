<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Shared\PriceProductOffer\PriceProductOfferConfig;
use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface;
use Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface;

class PriceProductOfferWriter implements PriceProductOfferWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface
     */
    protected $priceProductOfferEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface
     */
    protected $priceProductOfferRepository;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface $priceProductOfferEntityManager
     * @param \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface $priceProductOfferRepository
     */
    public function __construct(
        PriceProductOfferToPriceProductFacadeInterface $priceProductFacade,
        PriceProductOfferEntityManagerInterface $priceProductOfferEntityManager,
        PriceProductOfferRepositoryInterface $priceProductOfferRepository
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceProductOfferEntityManager = $priceProductOfferEntityManager;
        $this->priceProductOfferRepository = $priceProductOfferRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function saveProductOfferPrices(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setPrices($this->getPriceProductTransfers($productOfferTransfer))
            ->setIdProductConcrete($productOfferTransfer->getIdProductConcrete());
        $productConcreteTransfer = $this->priceProductFacade->persistProductConcretePriceCollection($productConcreteTransfer);

        $productOfferTransfer->setPrices($productConcreteTransfer->getPrices());

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePriceProductOfferRelation(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $idPriceProductOffer = $priceProductTransfer->requirePriceDimension()
            ->getPriceDimension()
            ->requireIdProductOffer()
            ->getIdPriceProductOffer();

        if ($idPriceProductOffer) {
            return $this->priceProductOfferEntityManager->updatePriceProductOfferRelation($priceProductTransfer);
        }

        return $this->priceProductOfferEntityManager->createPriceProductOfferRelation($priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getPriceProductTransfers(
        ProductOfferTransfer $productOfferTransfer
    ): ArrayObject {
        $priceProductTransfers = new ArrayObject();

        foreach ($productOfferTransfer->getPrices() as $priceProductTransfer) {
            $priceProductTransfer->requirePriceDimension();

            $priceProductDimensionTransfer = $priceProductTransfer->getPriceDimension();
            $priceProductDimensionTransfer->setIdProductOffer($productOfferTransfer->getIdProductOffer());
            $priceProductDimensionTransfer->setType(PriceProductOfferConfig::DIMENSION_TYPE_PRODUCT_OFFER);
            $priceProductTransfer->setPriceDimension($priceProductDimensionTransfer);
            $priceProductTransfer->setIdProduct($productOfferTransfer->getIdProductConcrete());
            $priceProductTransfers->append($priceProductTransfer);
        }

        return $priceProductTransfers;
    }
}
