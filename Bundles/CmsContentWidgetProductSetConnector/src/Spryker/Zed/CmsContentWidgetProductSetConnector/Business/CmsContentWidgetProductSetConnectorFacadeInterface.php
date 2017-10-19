<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductSetConnector\Business;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductSetConnector\Business\CmsContentWidgetProductSetConnectorBusinessFactory getFactory()
 */
interface CmsContentWidgetProductSetConnectorFacadeInterface
{
    /**
     * Specification:
     *  - maps given abstract sku list to corresponding primary keys
     *
     * @api
     *
     * @param array $keyList
     *
     * @return array
     */
    public function mapProductKeyList(array $keyList);
}
