<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsBlock;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CmsBlockConstants
{
    /**
     * Specification
     * - Defines project name for absolute path to template folder
     *
     * @api
     */
    public const PROJECT_NAMESPACE = 'PROJECT_NAMESPACE';

    /**
     * Specification
     * - Defines the name of constant of default Yves template theme
     *
     * @api
     */
    public const YVES_THEME = 'YVES_THEME';
}
