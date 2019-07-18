<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication\Stub;

use SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer;

class CustomEndpointTransfer extends AbstractTransfer
{
    public const CURRENCY_CODE = 'currencyCode';

    /**
     * @var string
     */
    protected $currencyCode;

    /**
     * @var array
     */
    protected $transferPropertyNameMap = [
        'currency_code' => 'currencyCode',
        'currencyCode' => 'currencyCode',
        'CurrencyCode' => 'currencyCode',
        'self' => 'self',
        'Self' => 'self',
        'links' => 'links',
        'Links' => 'links',
        'data' => 'data',
        'Data' => 'data',
    ];

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::CURRENCY_CODE => [
            'type' => 'string',
            'name_underscore' => 'currency_code',
            'is_collection' => false,
            'is_transfer' => false,
        ],
    ];
}
