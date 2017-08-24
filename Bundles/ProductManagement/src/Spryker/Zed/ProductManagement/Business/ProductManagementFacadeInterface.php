<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementBusinessFactory getFactory()
 */
interface ProductManagementFacadeInterface
{

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection();

    /**
     * Specification:
     * - Returns a filtered list of keys that exists in the persisted product attribute key list but not in the persisted
     * product management attribute list
     *
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestUnusedAttributeKeys($searchText = '', $limit = 10);

}
