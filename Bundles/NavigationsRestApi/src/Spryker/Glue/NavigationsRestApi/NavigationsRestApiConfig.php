<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class NavigationsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_NAVIGATION_TREES = 'navigation-trees';
    public const CONTROLLER_NAVIGATION_TREES = 'navigation-trees-resource';

    public const ACTION_NAVIGATION_TREES_GET = 'get';

    public const RESPONSE_CODE_NAVIGATION_TREE_NOT_FOUND = '1601';
    public const RESPONSE_CODE_NAVIGATION_TREE_KEY_IS_NOT_SPECIFIED = '1602';

    public const RESPONSE_DETAILS_NAVIGATION_TREE_NOT_FOUND = 'Navigation not found.';
    public const RESPONSE_DETAILS_NAVIGATION_TREE_KEY_IS_NOT_SPECIFIED = 'Navigation key not specified.';
}
