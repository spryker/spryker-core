<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Category;

interface CategoryConstants
{

    /**
     * @deprecated Use `CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE` instead.
     */
    const RESOURCE_TYPE_CATEGORY_NODE = 'categorynode';

    /**
     * @deprecated Use `CategoryConfig::RESOURCE_TYPE_NAVIGATION` instead.
     */
    const RESOURCE_TYPE_NAVIGATION = 'navigation';

    const PARAM_ID_NODE = 'id-node';
    const PARAM_ID_PARENT_NODE = 'id-parent-node';
    const PARAM_ID_CATEGORY = 'id-category';

    /**
     * Specification:
     * - A list of available templates for category view
     *
     * @api
     */
    const TEMPLATE_LIST = 'TEMPLATE_LIST';

    /**
     * Specification:
     * - Status of category template dispatcher (boolean)
     *
     * Template dispatcher requires addition client calls, which could affect category page performance
     * We recommend to turn off this option if you don't use category templating in your project.
     *
     * @api
     */
    const TEMPLATE_DISPATCHER_ENABLED = 'TEMPLATE_DISPATCHER_ENABLED';

}
