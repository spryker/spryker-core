<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

interface KernelConstants
{
    const BACKTRACE_USER_PATH = 'BACKTRACE_USER_PATH';

    const CORE_NAMESPACES = 'CORE_NAMESPACES';

    const PROJECT_NAMESPACE = 'PROJECT_NAMESPACE';
    const PROJECT_NAMESPACES = 'PROJECT_NAMESPACES';

    /** @deprecated Use Module Config class directly instead. */
    const SPRYKER_ROOT = 'SPRYKER_ROOT';

    const STORE_PREFIX = 'STORE_PREFIX';

    const DEPENDENCY_INJECTOR_YVES = 'DEPENDENCY_INJECTOR_YVES';
    const DEPENDENCY_INJECTOR_ZED = 'DEPENDENCY_INJECTOR_ZED';

    const AUTO_LOADER_UNRESOLVABLE_CACHE_ENABLED = 'ENABLE_AUTO_LOADER_UNRESOLVABLE_CACHE';
    const AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER = 'AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER';
    const AUTO_LOADER_CACHE_FILE_NO_LOCK = 'AUTO_LOADER_CACHE_FILE_NO_LOCK';

    const URL_WHITELIST = 'URL_WHITELIST';
}
