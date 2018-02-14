<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business;

interface ProductLabelStorageFacadeInterface
{
    /**
     * Specification:
     * - Stores label dictionary data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @return void
     */
    public function publishLabelDictionary();

    /**
     * Specification:
     * - Finds and deletes label dictionary storage entities
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @return void
     */
    public function unpublishLabelDictionary();

    /**
     * Specification:
     * - Queries all productLabels with the given productAbstractIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishProductLabel(array $productAbstractIds);

    /**
     * Specification:
     * - Finds and deletes productLabels storage entities with the given productAbstractIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductLabel(array $productAbstractIds);
}
