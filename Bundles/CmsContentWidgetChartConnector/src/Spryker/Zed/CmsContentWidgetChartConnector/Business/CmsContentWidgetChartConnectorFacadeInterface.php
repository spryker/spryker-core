<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetChartConnector\Business;

interface CmsContentWidgetChartConnectorFacadeInterface
{
    /**
     * Specification:
     *  - maps given chart key list to corresponding primary keys
     *
     * @api
     *
     * @param array $keyList
     *
     * @return array
     */
    public function mapChartKeyList(array $keyList);
}
