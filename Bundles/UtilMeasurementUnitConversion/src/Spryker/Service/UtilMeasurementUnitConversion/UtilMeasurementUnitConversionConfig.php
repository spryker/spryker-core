<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilMeasurementUnitConversion;

use Spryker\Service\Kernel\AbstractBundleConfig;

class UtilMeasurementUnitConversionConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Contains a list of exchange ratios.
     * - First level keys represent the base unit code of conversion,
     * - Second level keys represent the target unit code,
     * - Values are the corresponding exchange ratios.
     * - Conversion ratios are defined both forth and back and also to the same unit.
     *
     * @var array
     */
    protected const MEASUREMENT_UNIT_EXCHANGE_RATIO_MAP = [
        'KILO' => [
            'KILO' => 1,
            'GRAM' => 1000,
            'TONE' => 1000,
            'GBOU' => 35.274,
            'USOU' => 35.274,
            'PUND' => 2.2046,
            'GBTN' => 0.0009,
            'USTN' => 0.0011,
            'OZTR' => 32.1507,
        ],
        'GRAM' => [
            'KILO' => 0.001,
            'GRAM' => 1,
            'GBOU' => 0.0352,
            'USOU' => 0.0352,
            'PUND' => 0.0022,
            'OZTR' => 0.0321507,
        ],
        'TONE' => [
            'KILO' => 1000,
            'TONE' => 1,
            'GBOU' => 35274,
            'USOU' => 35274,
            'PUND' => 2204.62,
            'GBTN' => 0.984207,
            'USTN' => 1.10231,
            'OZTR' => 32150.7,
        ],
        'GBOU' => [
            'KILO' => 0.0283495,
            'GRAM' => 28.3495,
            'GBOU' => 1,
            'USOU' => 1,
            'PUND' => 0.0625,
            'GBTN' => 0.00002790179,
            'USTN' => 0.00003125,
            'OZTR' => 0.9114583,
        ],
        'USOU' => [
            'KILO' => 0.0283495,
            'GRAM' => 28.3495,
            'GBOU' => 1,
            'USOU' => 1,
            'PUND' => 0.0625,
            'GBTN' => 0.00002790179,
            'USTN' => 0.00003125,
            'OZTR' => 0.9114583,
        ],
        'PUND' => [
            'KILO' => 0.4535923,
            'GRAM' => 453.5923,
            'TONE' => 0.0004535923,
            'GBOU' => 16,
            'USOU' => 16,
            'PUND' => 1,
            'GBTN' => 0.0004464286,
            'USTN' => 0.0005,
            'OZTR' => 14.58333,
        ],
        'GBTN' => [
            'KILO' => 1016.047,
            'TONE' => 1.016047,
            'GBOU' => 35840,
            'USOU' => 35840,
            'PUND' => 2240,
            'GBTN' => 1,
            'USTN' => 1.12,
            'OZTR' => 32666.67,
        ],
        'USTN' => [
            'KILO' => 907.1847,
            'TONE' => 0.9071847,
            'GBOU' => 32000,
            'USOU' => 32000,
            'PUND' => 2000,
            'GBTN' => 0.8928572,
            'USTN' => 1,
            'OZTR' => 29166.67,
        ],
        'OZTR' => [
            'KILO' => 0.03110348,
            'GRAM' => 31.10348,
            'TONE' => 0.00003110348,
            'GBOU' => 1.097143,
            'USOU' => 1.097143,
            'PUND' => 0.06857143,
            'GBTN' => 0.00003061224,
            'USTN' => 0.00003428571,
            'OZTR' => 1,
        ],

        'METR' => [
            'METR' => 1,
            'CMET' => 100,
            'MMET' => 1000,
            'KMET' => 0.001,
            'INCH' => 39.37008,
            'YARD' => 1.093613,
            'FOOT' => 3.280840,
            'MILE' => 0.0006213712,
        ],
        'CMET' => [
            'METR' => 0.01,
            'CMET' => 1,
            'MMET' => 10,
            'INCH' => 0.3937008,
            'YARD' => 0.01093613,
            'FOOT' => 0.03280840,
        ],
        'MMET' => [
            'METR' => 0.001,
            'CMET' => 0.1,
            'MMET' => 1,
            'INCH' => 0.03937008,
            'YARD' => 0.001093613,
            'FOOT' => 0.003280840,
        ],
        'KMET' => [
            'METR' => 1000,
            'KMET' => 1,
            'INCH' => 39370.08,
            'YARD' => 1093.613,
            'FOOT' => 3280.840,
            'MILE' => 0.6213712,
        ],
        'INCH' => [
            'METR' => 0.0254,
            'CMET' => 2.54,
            'MMET' => 25.4,
            'KMET' => 0.0000254,
            'INCH' => 1,
            'YARD' => 0.02777778,
            'FOOT' => 0.08333333,
            'MILE' => 0.00001578283,
        ],
        'YARD' => [
            'METR' => 0.9144,
            'CMET' => 91.44,
            'MMET' => 914.4,
            'KMET' => 0.0009144,
            'INCH' => 36,
            'YARD' => 1,
            'FOOT' => 3,
            'MILE' => 0.0005681818,
        ],
        'FOOT' => [
            'METR' => 0.3048,
            'CMET' => 30.48,
            'MMET' => 304.8,
            'KMET' => 0.0003048,
            'INCH' => 12,
            'YARD' => 0.3,
            'FOOT' => 1,
            'MILE' => 0.0001893939,
        ],
        'MILE' => [
            'METR' => 1609.344,
            'KMET' => 1.609344,
            'INCH' => 63360,
            'YARD' => 1760,
            'FOOT' => 5280,
            'MILE' => 1,
        ],

        'SMET' => [
            'SMET' => 1,
            'SQKI' => 0.000001,
            'SMIL' => 1000000,
            'SCMT' => 10000,
            'SQIN' => 1550.003,
            'SQFO' => 10.76391,
            'SQMI' => 0.00000038610216,
            'SQYA' => 1.19599,
            'ACRE' => 0.0002471054,
            'ARES' => 0.01,
            'HECT' => 0.0001,
        ],
        'SQKI' => [
            'SMET' => 1000000,
            'SQKI' => 1,
            'SQMI' => 0.3861022,
            'SQYA' => 1195990,
            'ACRE' => 247.1054,
            'ARES' => 10000,
            'HECT' => 100,
        ],
        'SMIL' => [
            'SMET' => 0.000001,
            'SMIL' => 1,
            'SCMT' => 0.01,
            'SQIN' => 0.001550003,
            'SQFO' => 0.00001076391,
            'SQYA' => 0.000001195990,
        ],
        'SCMT' => [
            'SMET' => 0.0001,
            'SMIL' => 100,
            'SCMT' => 1,
            'SQIN' => 0.1550003,
            'SQFO' => 0.001076391,
            'SQYA' => 0.000119599,
        ],
        'SQIN' => [
            'SMET' => 0.00064516,
            'SMIL' => 645.16,
            'SCMT' => 6.4516,
            'SQIN' => 1,
            'SQFO' => 0.006944444,
            'SQYA' => 0.0007716049,
        ],
        'SQFO' => [
            'SMET' => 0.09290304,
            'SMIL' => 92903.04,
            'SCMT' => 929.0304,
            'SQIN' => 144,
            'SQFO' => 1,
            'SQYA' => 0.1111111,
            'ACRE' => 0.000022957,
            'ARES' => 0.00092903,
            'HECT' => 0.0000092903,
        ],
    ];

    /**
     * @return array
     */
    public function getMeasurementUnitExchangeRatioMap(): array
    {
        return static::MEASUREMENT_UNIT_EXCHANGE_RATIO_MAP;
    }
}
