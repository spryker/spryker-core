<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Dependency\Plugin;

interface ResourceWithParentPluginInterface
{
    /**
     * @api
     *
     * Indicates that resource relates/depend on other resource, should provide resource name it's linked
     * This is relationship when resources are nesting within same request path,
     * e.g. /carts/1/items. So items resource will have parent carts.
     *
     * This must be used together with resource routing plugin
     *
     * @return string
     */
    public function getParentResourceType(): string;
}
