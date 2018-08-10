<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionCollectionTransfer;

/**
 * @method \Spryker\Zed\Country\Business\CountryFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2CodeAction(CountryTransfer $countryTransfer): CountryTransfer
    {
        return $this->getFacade()->getCountryByIso2Code($countryTransfer->getIso2Code());
    }

    /**
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function getRegionsByCountryIso2CodeAction(CountryTransfer $countryTransfer): RegionCollectionTransfer
    {
        return $this->getFacade()->getRegionsByCountryIso2Code($countryTransfer->getIso2Code());
    }
}
