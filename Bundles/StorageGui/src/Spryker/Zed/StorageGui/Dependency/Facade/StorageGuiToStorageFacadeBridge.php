<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageGui\Dependency\Facade;

class StorageGuiToStorageFacadeBridge implements StorageGuiToStorageFacadeInterface
{
    /**
     * @var \Spryker\Zed\Storage\Business\StorageFacadeInterface
     */
    protected $storageFacade;

    /**
     * @param \Spryker\Zed\Storage\Business\StorageFacadeInterface $storageFacade
     */
    public function __construct($storageFacade)
    {
        $this->storageFacade = $storageFacade;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->storageFacade->get($key);
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->storageFacade->getTotalCount();
    }
}
