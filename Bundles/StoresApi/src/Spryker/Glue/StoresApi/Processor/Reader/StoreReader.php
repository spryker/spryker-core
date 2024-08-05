<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\StoreStorageTransfer;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToLocaleClientInterface;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreClientInterface;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface;
use Spryker\Glue\StoresApi\Processor\Builder\StoresApiResponseBuilderInterface;
use Spryker\Glue\StoresApi\Processor\Expander\StoreExpanderInterface;

class StoreReader implements StoreReaderInterface
{
    /**
     * @var \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface
     */
    protected StoresApiToStoreStorageClientInterface $storeStorageClient;

    /**
     * @var \Spryker\Glue\StoresApi\Processor\Builder\StoresApiResponseBuilderInterface
     */
    protected StoresApiResponseBuilderInterface $storesApiResponseBuilder;

    /**
     * @var \Spryker\Glue\StoresApi\Processor\Expander\StoreExpanderInterface
     */
    protected StoreExpanderInterface $storeExpander;

    /**
     * @var \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToLocaleClientInterface
     */
    protected StoresApiToLocaleClientInterface $localeClient;

    /**
     * @var \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreClientInterface
     */
    protected StoresApiToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface $storeStorageClient
     * @param \Spryker\Glue\StoresApi\Processor\Builder\StoresApiResponseBuilderInterface $storesApiResponseBuilder
     * @param \Spryker\Glue\StoresApi\Processor\Expander\StoreExpanderInterface $storeExpander
     * @param \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToLocaleClientInterface $localeClient
     * @param \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreClientInterface $storeClient
     */
    public function __construct(
        StoresApiToStoreStorageClientInterface $storeStorageClient,
        StoresApiResponseBuilderInterface $storesApiResponseBuilder,
        StoreExpanderInterface $storeExpander,
        StoresApiToLocaleClientInterface $localeClient,
        StoresApiToStoreClientInterface $storeClient
    ) {
        $this->storeStorageClient = $storeStorageClient;
        $this->storesApiResponseBuilder = $storesApiResponseBuilder;
        $this->storeExpander = $storeExpander;
        $this->localeClient = $localeClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param string $store
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getStore(string $store, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $currentLocale = $this->localeClient->getCurrentLocale();
        $storeStorageTransfer = $this->storeStorageClient->findStoreByName($store);

        /**
         * Required by infrastructure, exists only for BC reasons with DMS mode.
         */
        if (!$this->storeClient->isDynamicStoreEnabled()) {
            $storeTransfer = $this->storeClient->getCurrentStore();
            $storeStorageTransfer = $this->storesApiResponseBuilder
                ->mapStoreTransferToStoreStorageTransfer(
                    $storeTransfer,
                    new StoreStorageTransfer(),
                );
            $storeId = $glueRequestTransfer->getResourceOrFail()->getId();

            if ($storeId !== null && $storeId !== $storeStorageTransfer->getName()) {
                return $this->storesApiResponseBuilder->create404GlueResponseTransfer($currentLocale);
            }
        }

        $glueResponseTransfer = $this->storesApiResponseBuilder
            ->createSingleResourceGlueResponseTransfer(
                $currentLocale,
                new GlueResponseTransfer(),
                $storeStorageTransfer,
            );
        $storeStorageTransfers = [];
        if ($storeStorageTransfer !== null) {
            $storeStorageTransfers[] = $storeStorageTransfer;
        }
        $storesArray = $this->storesApiResponseBuilder->mapStoreStorageTransfersToStoresArray($storeStorageTransfers);

        return $this->expandGlueResponseTransfer($glueResponseTransfer, $storesArray);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getStoreCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        /**
         * Required by infrastructure, exists only for BC reasons with DMS mode.
         */
        if (!$this->storeClient->isDynamicStoreEnabled()) {
            $storeTransfer = $this->storeClient->getCurrentStore();
            $storeStorageTransfer = $this->storesApiResponseBuilder
                ->mapStoreTransferToStoreStorageTransfer(
                    $storeTransfer,
                    new StoreStorageTransfer(),
                );
            $storeStorageTransfers = [$storeStorageTransfer];

            return $this->getGlueResponseTransfer($storeStorageTransfers);
        }

        $stores = $this->storeStorageClient->getStoreNames();
        $storeStorageTransfers = [];

        foreach ($stores as $store) {
            $storeStorageTransfer = $this->storeStorageClient->findStoreByName($store);
            if ($storeStorageTransfer !== null) {
                $storeStorageTransfers[] = $storeStorageTransfer;
            }
        }

        return $this->getGlueResponseTransfer($storeStorageTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreStorageTransfer> $storeStorageTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function getGlueResponseTransfer(array $storeStorageTransfers): GlueResponseTransfer
    {
        $glueResponseTransfer = $this->storesApiResponseBuilder
            ->mapStoreStorageTransfersToCollectionResourceGlueResponseTransfer(
                $storeStorageTransfers,
                new GlueResponseTransfer(),
            );

        $storesArray = $this->storesApiResponseBuilder->mapStoreStorageTransfersToStoresArray($storeStorageTransfers);

        return $this->expandGlueResponseTransfer($glueResponseTransfer, $storesArray);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param array<string, mixed> $storesArray
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function expandGlueResponseTransfer(
        GlueResponseTransfer $glueResponseTransfer,
        array $storesArray
    ): GlueResponseTransfer {
        foreach ($glueResponseTransfer->getResources() as $glueResourceTransfer) {
            /** @var \Generated\Shared\Transfer\ApiStoreAttributesTransfer $apiStoreAttributesTransfer */
            $apiStoreAttributesTransfer = $glueResourceTransfer->getAttributes();
            $this->storeExpander->expandApiStoreAttributesTransfer(
                $apiStoreAttributesTransfer,
                $storesArray,
                $glueResourceTransfer,
            );
        }

        return $glueResponseTransfer;
    }
}
