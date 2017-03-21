<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CollectorStorageConnector\Dependency\Facade;

class CollectorStorageConnectorToStorageBridge implements CollectorStorageConnectorToStorageInterface
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
     * @return array
     */
    public function getTimestamps()
    {
        return $this->storageFacade->getTimestamps();
    }

}
