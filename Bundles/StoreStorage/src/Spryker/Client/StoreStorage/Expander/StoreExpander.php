<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\StoreStorage\Reader\StoreStorageReaderInterface;

class StoreExpander implements StoreExpanderInterface
{
    /**
     * @var \Spryker\Client\StoreStorage\Reader\StoreStorageReaderInterface
     */
    protected $storeReader;

    /**
     * @param \Spryker\Client\StoreStorage\Reader\StoreStorageReaderInterface $storeReader
     */
    public function __construct(StoreStorageReaderInterface $storeReader)
    {
        $this->storeReader = $storeReader;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function expandStore(StoreTransfer $storeTransfer): StoreTransfer
    {
        $storeStorageTransfer = $this->storeReader->findStoreByName($storeTransfer->getNameOrFail());

        if ($storeStorageTransfer === null) {
            return $storeTransfer;
        }

        return $storeTransfer->fromArray($storeStorageTransfer->toArray());
    }
}
