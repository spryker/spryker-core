<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductConnector\Business;

interface CmsProductConnectorFacadeInterface
{

    /**
     * Specification:
     *  - maps given abstract sku list to corresponding primary keys
     *
     * @api
     *
     * @param array $skuList
     *
     * @return array
     */
    public function mapProductSkuList(array $skuList);

}
