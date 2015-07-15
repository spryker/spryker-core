<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Business\Internal\Regions;

class UnitedStatesRegionInstaller implements RegionInstallInterface
{

    /**
     * {@inheritdoc}
     */
    public function getCodeArray()
    {
        return [
            'US-AL' => 'Alabama',
            'US-AK' => 'Alaska',
            'US-AZ' => 'Arizona',
            'US-AR' => 'Arkansas',
            'US-CA' => 'Kalifornien',
            'US-CO' => 'Colorado',
            'US-CT' => 'Connecticut',
            'US-DE' => 'Delaware',
            'US-DC' => 'Columbia',
            'US-FL' => 'Florida',
            'US-GA' => 'Georgia',
            'US-HI' => 'Hawaii',
            'US-ID' => 'Idaho',
            'US-IL' => 'Illinois',
            'US-IN' => 'Indiana',
            'US-IA' => 'Iowa',
            'US-KS' => 'Kansas',
            'US-KY' => 'Kentucky',
            'US-LA' => 'Louisiana',
            'US-ME' => 'Maine',
            'US-MD' => 'Maryland',
            'US-MA' => 'Massachusetts',
            'US-MI' => 'Michigan',
            'US-MN' => 'Minnesota',
            'US-MS' => 'Mississippi',
            'US-MO' => 'Missouri',
            'US-MT' => 'Montana',
            'US-NE' => 'Nebraska',
            'US-NV' => 'Nevada',
            'US-NH' => 'Hampshire',
            'US-NJ' => 'Jersey',
            'US-NM' => 'Mexico',
            'US-NY' => 'York',
            'US-NC' => 'Carolina',
            'US-ND' => 'Dakota',
            'US-OH' => 'Ohio',
            'US-OK' => 'Oklahoma',
            'US-OR' => 'Oregon',
            'US-PA' => 'Pennsylvania',
            'US-RI' => 'Island',
            'US-SC' => 'Carolina',
            'US-SD' => 'Dakota',
            'US-TN' => 'Tennessee',
            'US-TX' => 'Texas',
            'US-UT' => 'Utah',
            'US-VT' => 'Vermont',
            'US-VA' => 'Virginia',
            'US-WA' => 'Washington',
            'US-WV' => 'Virginia',
            'US-WI' => 'Wisconsin',
            'US-WY' => 'Wyoming',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryIso()
    {
        return 'US';
    }

}
