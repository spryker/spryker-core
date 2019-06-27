<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\BuildConfigProvider;

use Psr\Log\LoggerInterface;

interface YvesAssetsBuildConfigProviderInterface
{
    /**
     * @param string $storeName
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function generateYvesAssetsBuildConfig(string $storeName, LoggerInterface $logger): bool;
}
