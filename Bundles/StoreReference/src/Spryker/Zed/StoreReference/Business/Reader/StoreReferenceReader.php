<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreReference\Business\Reader;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException;
use Spryker\Zed\StoreReference\Dependency\Facade\StoreReferenceToStoreInterface;
use Spryker\Zed\StoreReference\StoreReferenceConfig;

class StoreReferenceReader implements StoreReferenceReaderInterface
{
    /**
     * @var \Spryker\Zed\StoreReference\StoreReferenceConfig
     */
    protected $storeReferenceConfig;

    /**
     * @var \Spryker\Zed\StoreReference\Dependency\Facade\StoreReferenceToStoreInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\StoreReference\StoreReferenceConfig $storeReferenceConfig
     * @param \Spryker\Zed\StoreReference\Dependency\Facade\StoreReferenceToStoreInterface $storeFacade
     */
    public function __construct(
        StoreReferenceConfig $storeReferenceConfig,
        StoreReferenceToStoreInterface $storeFacade
    ) {
        $this->storeReferenceConfig = $storeReferenceConfig;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $storeReference
     *
     * @throws \Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreReference(string $storeReference): StoreTransfer
    {
        $storeReferenceMap = array_flip($this->storeReferenceConfig->getStoreNameReferenceMap());

        if (empty($storeReferenceMap[$storeReference])) {
            throw new StoreReferenceNotFoundException(
                sprintf(
                    'Could not get a store name by store-reference %s. Please check your configuration in %s::getStoreNameReferenceMap()',
                    $storeReference,
                    StoreReferenceConfig::class,
                ),
            );
        }

        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        $storeTransfer = $this->storeFacade->getStoreByName((string)$storeReferenceMap[$storeReference]);
        $storeTransfer->setStoreReference($storeReference);

        return $storeTransfer;
    }

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreName(string $storeName): StoreTransfer
    {
        $storeReferenceMap = $this->storeReferenceConfig->getStoreNameReferenceMap();

        if (empty($storeReferenceMap[$storeName])) {
            throw new StoreReferenceNotFoundException(
                sprintf('StoreReference was not found by StoreName: %s', $storeName),
            );
        }

        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        $storeTransfer = $this->storeFacade->getStoreByName($storeName);
        $storeTransfer->setStoreReference($storeReferenceMap[$storeName]);

        return $storeTransfer;
    }

    /**
     * @throws \Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $storeName = $storeTransfer->getNameOrFail();
        $storeReferenceMap = $this->storeReferenceConfig->getStoreNameReferenceMap();

        if (empty($storeReferenceMap[$storeName])) {
            throw new StoreReferenceNotFoundException(
                sprintf('StoreReference was not found by StoreName: %s', $storeName),
            );
        }

        $storeTransfer->setStoreReference($storeReferenceMap[$storeName]);

        return $storeTransfer;
    }
}
