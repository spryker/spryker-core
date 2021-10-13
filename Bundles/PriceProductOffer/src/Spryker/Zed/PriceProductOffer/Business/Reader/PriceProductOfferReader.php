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
     * @var array<\Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExtractorPluginInterface>
     */
    protected $priceProductOfferExtractorPlugins;

    /**
     * @var array<\Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExpanderPluginInterface>
     */
    protected $priceProductOfferExpanderPlugins;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface $priceProductOfferRepository
     * @param array<\Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExtractorPluginInterface> $priceProductOfferExtractorPlugins
     * @param array<\Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExpanderPluginInterface> $priceProductOfferExpanderPlugins
     */
    public function __construct(
        PriceProductOfferRepositoryInterface $priceProductOfferRepository,
        array $priceProductOfferExtractorPlugins,
        array $priceProductOfferExpanderPlugins
    ) {
        $this->priceProductOfferRepository = $priceProductOfferRepository;
        $this->priceProductOfferExtractorPlugins = $priceProductOfferExtractorPlugins;
        $this->priceProductOfferExpanderPlugins = $priceProductOfferExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getProductOfferPrices(
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): ArrayObject {
        $priceProductTransfers = $this->priceProductOfferRepository
            ->getProductOfferPrices($priceProductOfferCriteriaTransfer)
            ->getArrayCopy();

        if ($priceProductOfferCriteriaTransfer->getWithExtractedPrices() !== false) {
            foreach ($this->priceProductOfferExtractorPlugins as $priceProductOfferExtractorPlugin) {
                $priceProductTransfers = array_merge(
                    $priceProductTransfers,
                    $priceProductOfferExtractorPlugin->extract($priceProductTransfers)
                );
            }
        }

        foreach ($this->priceProductOfferExpanderPlugins as $priceProductOfferExpanderPlugin) {
            foreach ($priceProductTransfers as $priceProductTransfer) {
                $priceProductOfferExpanderPlugin->expand($priceProductTransfer);
            }
        }

        return new ArrayObject($priceProductTransfers);
    }
}
