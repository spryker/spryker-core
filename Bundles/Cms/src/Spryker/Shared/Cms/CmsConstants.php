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

    /**
     * @var string
     */
    public const RESOURCE_TYPE_PAGE = 'page';
    /**
     * @var string
     */
    public const RESOURCE_TYPE_BLOCK = 'block';
    /**
     * @var string
     */
    public const RESOURCE_TYPE_CATEGORY_NODE = 'category';
    /**
     * @var string
     */
    public const RESOURCE_TYPE_STATIC = 'static';

    /**
     * @deprecated Use {@link \Spryker\Zed\Cms\CmsConfig::getThemeName()} instead.
     * @var string
     */
    public const YVES_THEME = 'YVES_THEME';
}
