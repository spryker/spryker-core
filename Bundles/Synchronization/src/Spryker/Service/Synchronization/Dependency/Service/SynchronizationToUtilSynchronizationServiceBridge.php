<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization\Dependency\Service;

class SynchronizationToUtilSynchronizationServiceBridge implements SynchronizationToUtilSynchronizationServiceInterface
{

    /**
     * @var \Spryker\Service\UtilSynchronization\UtilSynchronizationServiceInterface
     */
    protected $utilSynchronization;

    /**
     * @param \Spryker\Service\UtilSynchronization\UtilSynchronizationServiceInterface $utilSynchronization
     */
    public function __construct($utilSynchronization)
    {
        $this->utilSynchronization = $utilSynchronization;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function escapeKey($key)
    {
        return $this->utilSynchronization->escapeKey($key);
    }

}
