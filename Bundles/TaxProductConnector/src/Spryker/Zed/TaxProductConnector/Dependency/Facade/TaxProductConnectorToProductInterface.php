<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Dependency\Facade;

interface TaxProductConnectorToProductInterface
{

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductActive($idProductAbstract);

    /**
     * @return string
     */
//    public function getDefaultTaxCountryIso2Code();

    /**
     * @return float
     */
//    public function getDefaultTaxRate();

}
