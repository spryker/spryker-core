<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Internal\Regions;

class GermanyRegionInstaller implements RegionInstallInterface
{
    /**
     * @return string[]
     */
    public function getCodeArray()
    {
        return [
            'DE-BW' => 'Baden-Württemberg',
            'DE-BY' => 'Bayern',
            'DE-BE' => 'Berlin',
            'DE-BB' => 'Brandenburg',
            'DE-HB' => 'Bremen',
            'DE-HH' => 'Hamburg',
            'DE-HE' => 'Hessen',
            'DE-MV' => 'Mecklenburg-Vorpommern',
            'DE-NI' => 'Niedersachsen',
            'DE-NW' => 'Nordrhein-Westfalen',
            'DE-RP' => 'Rheinland-Pfalz',
            'DE-SL' => 'Saarland',
            'DE-SN' => 'Sachsen',
            'DE-ST' => 'Sachsen-Anhalt',
            'DE-SH' => 'Schleswig-Holstein',
            'DE-TH' => 'Thüringen',
        ];
    }

    /**
     * @return string
     */
    public function getCountryIso()
    {
        return 'DE';
    }
}
