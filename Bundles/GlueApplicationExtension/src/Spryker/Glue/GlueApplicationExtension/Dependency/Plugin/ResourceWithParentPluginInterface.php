<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

interface ResourceWithParentPluginInterface
{
    /**
     * Specification:
     * - Indicates that resource relates/depends on another resource, should provide resource name it's linked
     * This is relationship when resources are nesting within same request path,
     * e.g. /carts/1/items. So items resource will have parent carts.
     *
     * This must be used together with resource routing plugin
     *
     * @api
     *
     * @return string
     */
    public function getParentResourceType(): string;
}
