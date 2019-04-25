<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StorageRouterExtension\Dependency\Plugin;

/**
 * Use this interface to map a resource type e.g. `product_abstract` from a storage result to a controller.
 * When this plugin matches to a resource type the matched route parameters will be enhanced with the `_controller` parameter.
 */
interface ResourceCreatorPluginInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getType(): string;

    /**
     * @api
     *
     * @return string
     */
    public function getModuleName(): string;

    /**
     * @api
     *
     * @return string
     */
    public function getControllerName(): string;

    /**
     * @api
     *
     * @return string
     */
    public function getActionName(): string;

    /**
     * @api
     *
     * @param array $data
     *
     * @return array
     */
    public function mergeResourceData(array $data): array;
}
