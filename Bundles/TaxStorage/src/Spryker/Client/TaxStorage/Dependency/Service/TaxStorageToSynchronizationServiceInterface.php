<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxStorage\Dependency\Service;

use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;

interface TaxStorageToSynchronizationServiceInterface
{
    /**
     * @param string $resourceName
     *
     * @return \Spryker\Client\TaxStorage\Dependency\Service\SynchronizationKeyGeneratorPluginInterface
     */
    public function getStorageKeyBuilder($resourceName): SynchronizationKeyGeneratorPluginInterface;
}
