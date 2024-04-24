<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDynamicEntityConnector\Business;

use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;

/**
 * @method \Spryker\Zed\ProductDynamicEntityConnector\Business\ProductDynamicEntityConnectorBusinessFactory getFactory()
 */
interface ProductDynamicEntityConnectorFacadeInterface
{
    /**
     * Specification:
     * - Requires `DynamicEntityPostEditRequest.tableName` to be set.
     * - Checks if `DynamicEntityPostEditRequest.tableName` is set to one of applicable table names.
     * - Requires `RawDynamicEntity.fields` to be set for each element in `DynamicEntityPostEditRequest.rawDynamicEntities`.
     * - Checks if `spy_product_abstract_localized_attributes` is created and creates product abstract URLs.
     * - Creates or updates product abstract URL by dynamic entity request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    public function updateProductAbstractUrlByDynamicEntityRequest(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer;
}
