<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface KernelConstants
{
    public const BACKTRACE_USER_PATH = 'BACKTRACE_USER_PATH';

    public const CORE_NAMESPACES = 'CORE_NAMESPACES';

    public const PROJECT_NAMESPACE = 'PROJECT_NAMESPACE';
    public const PROJECT_NAMESPACES = 'PROJECT_NAMESPACES';

    /**
     * @deprecated Use Module Config class directly instead.
     */
    public const SPRYKER_ROOT = 'SPRYKER_ROOT';

    public const STORE_PREFIX = 'STORE_PREFIX';

    public const DEPENDENCY_INJECTOR_YVES = 'DEPENDENCY_INJECTOR_YVES';
    public const DEPENDENCY_INJECTOR_ZED = 'DEPENDENCY_INJECTOR_ZED';

    /**
     * @deprecated Use {@link \Spryker\Shared\Kernel\KernelConstants::RESOLVABLE_CLASS_NAMES_CACHE_ENABLED} instead.
     */
    public const AUTO_LOADER_UNRESOLVABLE_CACHE_ENABLED = 'ENABLE_AUTO_LOADER_UNRESOLVABLE_CACHE';

    /**
     * @deprecated Use {@link \Spryker\Shared\Kernel\KernelConstants::RESOLVABLE_CLASS_NAMES_CACHE_ENABLED} instead.
     */
    public const AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER = 'AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER';
    /**
     * @deprecated Will be removed without replacement.
     *
     * @api
     */
    public const AUTO_LOADER_CACHE_FILE_NO_LOCK = 'AUTO_LOADER_CACHE_FILE_NO_LOCK';
    /**
     * @deprecated Will be removed without replacement.
     *
     * @api
     */
    public const AUTO_LOADER_CACHE_FILE_PATH = 'KERNEL:AUTO_LOADER_CACHE_FILE_PATH';

    /**
     * Specification:
     * - When enabled the ClassResolver tries to use a pre-build cache for resolvable class names.
     * - Use {@link \Spryker\Zed\Kernel\Communication\Console\ResolvableClassCacheConsole} and set this configuration to `true` to improve performance.
     *
     * @api
     */
    public const RESOLVABLE_CLASS_NAMES_CACHE_ENABLED = 'KERNEL:RESOLVABLE_CLASS_NAMES_CACHE_ENABLED';

    /**
     * Specification:
     * - When enabled the ClassResolver caches resolved class instances.
     * - Enable this to improve performance.
     *
     * @api
     */
    public const RESOLVED_INSTANCE_CACHE_ENABLED = 'KERNEL:RESOLVED_INSTANCE_CACHE_ENABLED';

    /**
     * Specification:
     * - Defines a set of whitelist domains, that every external URL is checked against, before redirect.
     *
     * @api
     */
    public const DOMAIN_WHITELIST = 'KERNEL:DOMAIN_WHITELIST';

    /**
     * Specification:
     * - Enables/disables strict external redirect check.
     * - When enabled, only the domains from whitelist are allowed to be used as a destination for external redirect.
     *
     * @api
     */
    public const STRICT_DOMAIN_REDIRECT = 'KERNEL:STRICT_DOMAIN_REDIRECT';

    /**
     * Specification:
     * - Defines the mode when a dependency container can be overridden, e.g. for testing needs.
     * - This should set to `true` to be able to use \Spryker\Shared\Kernel\ContainerMocker\ContainerGlobals
     *   via \SprykerTest\Shared\Testify\Helper\DependencyHelper for overriding dependencies in container for testing.
     *
     * @api
     */
    public const ENABLE_CONTAINER_OVERRIDING = 'KERNEL:ENABLE_CONTAINER_OVERRIDING';

    /**
     * Specification:
     * - Sets the permission mode for generated directories.
     *
     * @api
     */
    public const DIRECTORY_PERMISSION = 'KERNEL:DIRECTORY_PERMISSION';
}
