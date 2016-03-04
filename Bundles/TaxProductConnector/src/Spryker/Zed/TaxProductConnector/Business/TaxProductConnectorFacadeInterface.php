<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business;

interface TaxProductConnectorFacadeInterface
{

    /**
     * @api
     *
     * @return \Spryker\Zed\TaxProductConnector\Business\Plugin\TaxChangeTouchPlugin
     */
    public function getTaxChangeTouchPlugin();

}
