<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication\Stub;

use SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer;

class EndpointTransfer extends AbstractTransfer
{
    public const EMAIL_FIELD = 'emailField';
    public const STRING_FIELD = 'stringField';
    public const INTEGER_FIELD = 'integerField';

    /**
     * @var string
     */
    protected $emailField;

    /**
     * @var string
     */
    protected $stringField;

    /**
     * @var int
     */
    protected $integerField;

    /**
     * @var array
     */
    protected $transferPropertyNameMap = [
        'email_field' => 'emailField',
        'emailField' => 'emailField',
        'EmailField' => 'emailField',
        'string_field' => 'stringField',
        'stringField' => 'stringField',
        'StringField' => 'stringField',
        'integer_field' => 'integerField',
        'integerField' => 'integerField',
        'IntegerField' => 'integerField',
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
        self::EMAIL_FIELD => [
            'type' => 'string',
            'name_underscore' => 'email_field',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::STRING_FIELD => [
            'type' => 'string',
            'name_underscore' => 'string_field',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::INTEGER_FIELD => [
            'type' => 'int',
            'name_underscore' => 'integer_field',
            'is_collection' => false,
            'is_transfer' => false,
        ],
    ];
}
