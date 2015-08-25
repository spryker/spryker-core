<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerEngine\Shared\Kernel;

use SprykerFeature\Shared\Library\ConfigInterface;

interface KernelConfig extends ConfigInterface
{
    const CLASS_RESOLVER_CACHE_ENABLED = 'CLASS_RESOLVER_CACHE_ENABLED';
}