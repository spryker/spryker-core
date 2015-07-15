<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Business\Internal\Regions;

class GermanyRegionInstaller implements RegionInstallInterface
{

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getCountryIso()
    {
        return 'DE';
    }

}
