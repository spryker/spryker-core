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

    /** @deprecated Use Module Config class directly instead. */
    public const SPRYKER_ROOT = 'SPRYKER_ROOT';

    public const STORE_PREFIX = 'STORE_PREFIX';

    public const DEPENDENCY_INJECTOR_YVES = 'DEPENDENCY_INJECTOR_YVES';
    public const DEPENDENCY_INJECTOR_ZED = 'DEPENDENCY_INJECTOR_ZED';

    public const AUTO_LOADER_UNRESOLVABLE_CACHE_ENABLED = 'ENABLE_AUTO_LOADER_UNRESOLVABLE_CACHE';
    public const AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER = 'AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER';
    public const AUTO_LOADER_CACHE_FILE_NO_LOCK = 'AUTO_LOADER_CACHE_FILE_NO_LOCK';
    public const AUTO_LOADER_CACHE_FILE_PATH = 'KERNEL:AUTO_LOADER_CACHE_FILE_PATH';

    /**
     * A set of whitelist domains, that every external URL is checked against, before redirect.
     */
    public const DOMAIN_WHITELIST = 'KERNEL:DOMAIN_WHITELIST';
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
}
