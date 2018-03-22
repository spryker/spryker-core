<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business;

interface CategoryStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all category nodes with categoryNodeIds
     * - Creates a data structure tree
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds);

    /**
     * Specification:
     * - Finds and deletes category node storage entities with categoryNodeIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds);

    /**
     * Specification:
     * - Queries all categories
     * - Creates a data structure category tree
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @return void
     */
    public function publishCategoryTree();

    /**
     * Specification:
     * - Finds and deletes all category tree storage entities
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @return void
     */
    public function unpublishCategoryTree();
}
