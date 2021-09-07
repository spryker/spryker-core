<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class NavigationsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_NAVIGATIONS = 'navigations';
    /**
     * @var string
     */
    public const CONTROLLER_NAVIGATIONS = 'navigations-resource';

    /**
     * @var string
     */
    public const ACTION_NAVIGATIONS_GET = 'get';

    /**
     * @var string
     */
    public const RESPONSE_CODE_NAVIGATION_NOT_FOUND = '1601';
    /**
     * @var string
     */
    public const RESPONSE_CODE_NAVIGATION_ID_IS_NOT_SPECIFIED = '1602';

    /**
     * @var string
     */
    public const RESPONSE_DETAILS_NAVIGATION_NOT_FOUND = 'Navigation not found.';
    /**
     * @var string
     */
    public const RESPONSE_DETAILS_NAVIGATION_ID_IS_NOT_SPECIFIED = 'Navigation id not specified.';

    /**
     * This method provides mappings for navigation node type to url resource id field.
     * It is used to define which field from \Generated\Shared\Transfer\UrlStorageTransfer contains node id value
     * for node with specified node type.
     *
     * example:
     * [
     *    'category' => 'fkResourceCategorynode',
     * ]
     *
     * @api
     *
     * @return string[]
     */
    public function getNavigationTypeToUrlResourceIdFieldMapping(): array
    {
        return [];
    }
}
