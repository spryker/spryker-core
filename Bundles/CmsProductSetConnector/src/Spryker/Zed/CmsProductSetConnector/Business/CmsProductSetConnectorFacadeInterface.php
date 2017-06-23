<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductSetConnector\Business;

/**
 * @method \Spryker\Zed\CmsProductSetConnector\Business\CmsProductSetConnectorBusinessFactory getFactory()
 */
interface CmsProductSetConnectorFacadeInterface
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
    public function mapProductKeyList(array $skuList);

}
