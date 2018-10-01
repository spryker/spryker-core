<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Cms;

use Spryker\Shared\Kernel\KernelConstants;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CmsConstants
{
    public const PROJECT_NAMESPACE = KernelConstants::PROJECT_NAMESPACE;

    public const RESOURCE_TYPE_PAGE = 'page';
    public const RESOURCE_TYPE_BLOCK = 'block';
    public const RESOURCE_TYPE_CATEGORY_NODE = 'category';
    public const RESOURCE_TYPE_STATIC = 'static';

    public const YVES_THEME = 'YVES_THEME';
}
