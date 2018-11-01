<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business;

interface ProductPageSearchFacadeInterface
{
    /**
     * Specification:
     * - Queries all productAbstract with the given productAbstractIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds);

    /**
     * Specification:
     * - Queries all productAbstract with the given productAbstractIds
     * - Stores and update data partially as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     * - $pageDataExpanderPluginNames param is optional and if it's empty
     *      it will call all provided plugins, otherwise only update necessary part
     *      of data which provide in plugin name.
     *
     * @api
     *
     * @param array $productAbstractIds
     * @param array $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, $pageDataExpanderPluginNames = []);

    /**
     * Specification:
     * - Finds and deletes productAbstract storage entities with the given productAbstractIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds);
}
