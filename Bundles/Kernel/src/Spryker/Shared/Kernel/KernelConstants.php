<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

use Spryker\Shared\Application\ApplicationConstants;

interface KernelConstants
{

    const BACKTRACE_USER_PATH = ApplicationConstants::BACKTRACE_USER_PATH;

    const CORE_NAMESPACES = ApplicationConstants::CORE_NAMESPACES;

    const PROJECT_NAMESPACE = ApplicationConstants::PROJECT_NAMESPACE;
    const PROJECT_NAMESPACES = ApplicationConstants::PROJECT_NAMESPACES;
    const PROJECT_TIMEZONE = ApplicationConstants::PROJECT_TIMEZONE;

    /**
     * @deprecated Use PropelConstants::PROPEL instead.
     */
    const PROPEL = 'PROPEL';

    const STORE_PREFIX = ApplicationConstants::STORE_PREFIX;

    const DEPENDENCY_INJECTOR_YVES = 'DEPENDENCY_INJECTOR_YVES';
    const DEPENDENCY_INJECTOR_ZED = 'DEPENDENCY_INJECTOR_ZED';

    const AUTO_LOADER_UNRESOLVABLE_CACHE_ENABLED = 'ENABLE_AUTO_LOADER_UNRESOLVABLE_CACHE';
    const AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER = 'AUTO_LOADER_UNRESOLVABLE_CACHE_PROVIDER';
    const AUTO_LOADER_CACHE_FILE_NO_LOCK = 'AUTO_LOADER_CACHE_FILE_NO_LOCK';

    /**
     * @deprecated Use `$this->getProvidedDependency(ApplicationConstants::FORM_FACTORY)` to get the form factory.
     */
    const FORM_FACTORY = 'FORM_FACTORY';

}
