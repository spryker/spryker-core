<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub;

use SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer;

class RestTestAttributesTransfer extends AbstractTransfer
{
    public const ATTRIBUTE1 = 'attribute1';

    public const ATTRIBUTE2 = 'attribute2';

    /**
     * @var string
     */
    protected $attribute1;

    /**
     * @var string
     */
    protected $attribute2;

    /**
     * @var array
     */
    protected $transferPropertyNameMap = [
        'attribute1' => 'attribute1',
        'Attribute1' => 'attribute1',
        'attribute2' => 'attribute2',
        'Attribute2' => 'Attribute2',
    ];

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::ATTRIBUTE1 => [
            'type' => 'string',
            'name_underscore' => 'attribute1',
            'is_collection' => false,
            'is_transfer' => false,
            'rest_request_parameter' => 'no',
            'is_nullable' => false,
        ],
        self::ATTRIBUTE2 => [
            'type' => 'string',
            'name_underscore' => 'attribute2',
            'is_collection' => false,
            'is_transfer' => false,
            'rest_request_parameter' => 'required',
            'is_nullable' => false,
        ],
    ];

    /**
     * @return string|null
     */
    public function getAttribute1(): ?string
    {
        return $this->attribute1;
    }

    /**
     * @param string $attribute1
     *
     * @return $this
     */
    public function setAttribute1(string $attribute1)
    {
        $this->attribute1 = $attribute1;
        $this->modifiedProperties[self::ATTRIBUTE1] = true;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttribute2(): ?string
    {
        return $this->attribute2;
    }

    /**
     * @param string $attribute2
     *
     * @return $this
     */
    public function setAttribute2(string $attribute2)
    {
        $this->attribute2 = $attribute2;
        $this->modifiedProperties[self::ATTRIBUTE2] = true;

        return $this;
    }
}
