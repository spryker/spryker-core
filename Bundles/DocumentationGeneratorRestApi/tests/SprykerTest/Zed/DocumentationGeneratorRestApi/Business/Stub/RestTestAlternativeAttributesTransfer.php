<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub;

use SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer;

class RestTestAlternativeAttributesTransfer extends AbstractTransfer
{
    public const ATTRIBUTE3 = 'attribute3';

    public const ATTRIBUTE4 = 'attribute4';

    /**
     * @var string
     */
    protected $attribute3;

    /**
     * @var string
     */
    protected $attribute4;

    /**
     * @var array
     */
    protected $transferPropertyNameMap = [
        'attribute3' => 'attribute3',
        'Attribute3' => 'attribute3',
        'attribute4' => 'attribute4',
        'Attribute4' => 'Attribute4',
    ];

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::ATTRIBUTE3 => [
            'type' => 'string',
            'name_underscore' => 'attribute3',
            'is_collection' => false,
            'is_transfer' => false,
            'rest_request_parameter' => 'no',
            'is_nullable' => false,
        ],
        self::ATTRIBUTE4 => [
            'type' => 'string',
            'name_underscore' => 'attribute4',
            'is_collection' => false,
            'is_transfer' => false,
            'rest_request_parameter' => 'required',
            'is_nullable' => false,
        ],
    ];

    /**
     * @return string|null
     */
    public function getAttribute3(): ?string
    {
        return $this->attribute3;
    }

    /**
     * @param string $attribute3
     *
     * @return $this
     */
    public function setAttribute3(string $attribute3)
    {
        $this->attribute3 = $attribute3;
        $this->modifiedProperties[self::ATTRIBUTE3] = true;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttribute4(): ?string
    {
        return $this->attribute4;
    }

    /**
     * @param string $attribute4
     *
     * @return $this
     */
    public function setAttribute4(string $attribute4)
    {
        $this->attribute4 = $attribute4;
        $this->modifiedProperties[self::ATTRIBUTE4] = true;

        return $this;
    }
}
