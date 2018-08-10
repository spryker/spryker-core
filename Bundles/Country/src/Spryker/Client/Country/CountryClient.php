<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Country;

use Generated\Shared\Transfer\CountryResponseTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Country\CountryFactory getFactory()
 */
class CountryClient extends AbstractClient implements CountryClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function getCountryByIso2Code(CountryTransfer $countryTransfer): CountryTransfer
    {
        return $this->getFactory()
            ->createZedCountryStub()
            ->getCountryByIso2Code($countryTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function getRegionsByCountryIso2Code(CountryTransfer $countryTransfer): RegionCollectionTransfer
    {
        return $this->getFactory()
            ->createZedCountryStub()
            ->getRegionsByCountryIso2Code($countryTransfer);
    }
}
