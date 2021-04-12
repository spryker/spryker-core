<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Reader;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;

class ProductOfferReader implements ProductOfferReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface
     */
    protected $productOfferRepository;

    /**
     * @var array|\Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferExpanderPluginInterface[]
     */
    protected $productOfferExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface $productOfferRepository
     * @param \Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferExpanderPluginInterface[] $productOfferExpanderPlugins
     */
    public function __construct(
        ProductOfferRepositoryInterface $productOfferRepository,
        array $productOfferExpanderPlugins = []
    ) {
        $this->productOfferRepository = $productOfferRepository;
        $this->productOfferExpanderPlugins = $productOfferExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaFilterTransfer $productOfferCriteriaFilter): ?ProductOfferTransfer
    {
        $productOfferTransfer = $this->productOfferRepository->findOne($productOfferCriteriaFilter);

        if (!$productOfferTransfer) {
            return null;
        }

        $productOfferTransfer = $this->executeProductOfferExpanderPlugins($productOfferTransfer);

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function executeProductOfferExpanderPlugins(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        foreach ($this->productOfferExpanderPlugins as $productOfferExpanderPlugin) {
            $productOfferTransfer = $productOfferExpanderPlugin->expand($productOfferTransfer);
        }

        return $productOfferTransfer;
    }
}
