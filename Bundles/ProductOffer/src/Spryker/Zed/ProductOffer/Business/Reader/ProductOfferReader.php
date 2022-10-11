<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;

class ProductOfferReader implements ProductOfferReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface
     */
    protected $productOfferRepository;

    /**
     * @var array<\Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferExpanderPluginInterface>
     */
    protected $productOfferExpanderPlugins;

    /**
     * @var \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface $productOfferRepository
     * @param \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface $storeFacade
     * @param array $productOfferExpanderPlugins
     */
    public function __construct(
        ProductOfferRepositoryInterface $productOfferRepository,
        ProductOfferToStoreFacadeInterface $storeFacade,
        array $productOfferExpanderPlugins = []
    ) {
        $this->productOfferRepository = $productOfferRepository;
        $this->productOfferExpanderPlugins = $productOfferExpanderPlugins;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteria
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaTransfer $productOfferCriteria): ?ProductOfferTransfer
    {
        $productOfferTransfer = $this->productOfferRepository->findOne($productOfferCriteria);

        if (!$productOfferTransfer) {
            return null;
        }

        $productOfferTransfer = $this->executeProductOfferExpanderPlugins($productOfferTransfer);

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteria
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function get(ProductOfferCriteriaTransfer $productOfferCriteria): ProductOfferCollectionTransfer
    {
        $productOfferCollectionTransfer = $this->productOfferRepository->get($productOfferCriteria);

        // Load full Store transfers
        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $storeTransfers = new ArrayObject();
            foreach ($productOfferTransfer->getStores() as $storeTransfer) {
                $storeTransfers->append($this->storeFacade->getStoreByName($storeTransfer->getName()));
            }

            $productOfferTransfer->setStores($storeTransfers);
        }

        return $productOfferCollectionTransfer;
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
