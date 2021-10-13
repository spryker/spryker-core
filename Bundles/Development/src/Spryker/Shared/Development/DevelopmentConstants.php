<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Development;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface DevelopmentConstants
{
    /**
     * @var string
     */
    public const COMPOSER_REQUIRE_VERSION = 'COMPOSER_REQUIRE_VERSION';
    /**
     * @var string
     */
    public const COMPOSER_REQUIRE_VERSION_EXTERNAL = 'COMPOSER_REQUIRE_VERSION_EXTERNAL';
    /**
     * @var string
     */
    public const COMPOSER_BRANCH_ALIAS = 'COMPOSER_BRANCH_ALIAS';

    /**
     * @see \Spryker\Shared\Kernel\KernelConstants::PROJECT_NAMESPACES
     * @var string
     */
    public const PROJECT_NAMESPACES = 'PROJECT_NAMESPACES';

    /**
     * @see \Spryker\Shared\Kernel\KernelConstants::CORE_NAMESPACES
     * @var string
     */
    public const CORE_NAMESPACES = 'CORE_NAMESPACES';

    /**
     * Specification:
     * - Sets the permission mode for generated directories.
     *
     * @api
     * @var string
     */
    public const DIRECTORY_PERMISSION = 'DEVELOPMENT:DIRECTORY_PERMISSION';
}
