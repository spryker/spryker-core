<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface;

class PriceProductOfferReader implements PriceProductOfferReaderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface
     */
    protected $priceProductOfferRepository;

    /**
     * @var \Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExtractorPluginInterface[]
     */
    protected $priceProductOfferExtractorPlugins;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface $priceProductOfferRepository
     * @param \Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExtractorPluginInterface[] $priceProductOfferExtractorPlugins
     */
    public function __construct(
        PriceProductOfferRepositoryInterface $priceProductOfferRepository,
        array $priceProductOfferExtractorPlugins
    ) {
        $this->priceProductOfferRepository = $priceProductOfferRepository;
        $this->priceProductOfferExtractorPlugins = $priceProductOfferExtractorPlugins;
    }

    /**
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getProductOfferPrices(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): ArrayObject
    {
        $priceProductTransfers = $this->priceProductOfferRepository
            ->getProductOfferPrices($priceProductOfferCriteriaTransfer)
            ->getArrayCopy();

        foreach ($this->priceProductOfferExtractorPlugins as $priceProductOfferExtractorPlugin) {
            $priceProductTransfers = array_merge(
                $priceProductTransfers,
                $priceProductOfferExtractorPlugin->extract($priceProductTransfers)
            );
        }

        return new ArrayObject($priceProductTransfers);
    }
}
