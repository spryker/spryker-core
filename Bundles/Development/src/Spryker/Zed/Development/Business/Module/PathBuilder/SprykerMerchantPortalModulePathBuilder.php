<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\PathBuilder;

use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Development\DevelopmentConfig;

class SprykerMerchantPortalModulePathBuilder extends AbstractPathBuilder
{
    /**
     * @var string
     */
    protected const ORGANIZATION = 'SprykerMerchantPortal';

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function buildPaths(ModuleTransfer $moduleTransfer): array
    {
        $paths = [
            sprintf('%s%s', $this->config->getPathToInternalNamespace(static::ORGANIZATION), $moduleTransfer->getName()),
        ];

        return $paths;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return bool
     */
    public function accept(ModuleTransfer $moduleTransfer): bool
    {
        return ($moduleTransfer->getOrganization()->getName() === static::ORGANIZATION);
    }
}
