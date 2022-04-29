<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\InfrastructuralPermissionPluginInterface;
use Spryker\Shared\SharedCart\SharedCartConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 */
class WriteSharedCartPermissionPlugin extends AbstractPlugin implements ExecutablePermissionPluginInterface, InfrastructuralPermissionPluginInterface
{
    /**
     * @var string
     */
    public const KEY = 'WriteSharedCartPermissionPlugin';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string, mixed> $configuration
     * @param int|null $context ID quote.
     *
     * @return bool
     */
    public function can(array $configuration, $context = null)
    {
        if (!$context) {
            return false;
        }

        return in_array($context, $configuration[SharedCartConfig::PERMISSION_CONFIG_ID_QUOTE_COLLECTION]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getConfigurationSignature()
    {
        return [];
    }
}
