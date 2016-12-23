<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Cms;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;

interface CmsConstants
{

    const PROJECT_NAMESPACE = KernelConstants::PROJECT_NAMESPACE;

    const RESOURCE_TYPE_PAGE = 'page';
    const RESOURCE_TYPE_BLOCK = 'block';
    const RESOURCE_TYPE_CATEGORY_NODE = 'category';
    const RESOURCE_TYPE_STATIC = 'static';

    const YVES_THEME = ApplicationConstants::YVES_THEME;

}
