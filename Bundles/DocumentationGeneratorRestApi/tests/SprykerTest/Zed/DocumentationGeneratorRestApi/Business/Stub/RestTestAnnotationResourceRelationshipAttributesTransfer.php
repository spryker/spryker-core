<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub;

use SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer;

class RestTestAnnotationResourceRelationshipAttributesTransfer extends AbstractTransfer
{
    public const ATTRIBUTE5 = 'attribute5';

    public const ATTRIBUTE6 = 'attribute6';

    /**
     * @var string
     */
    protected $attribute5;

    /**
     * @var string
     */
    protected $attribute6;

    /**
     * @var array
     */
    protected $transferPropertyNameMap = [
        'attribute5' => 'attribute5',
        'Attribute5' => 'attribute5',
        'attribute6' => 'attribute6',
        'Attribute6' => 'Attribute6',
    ];

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::ATTRIBUTE5 => [
            'type' => 'string',
            'name_underscore' => 'attribute5',
            'is_collection' => false,
            'is_transfer' => false,
            'rest_request_parameter' => 'no',
            'is_nullable' => false,
        ],
        self::ATTRIBUTE6 => [
            'type' => 'string',
            'name_underscore' => 'attribute6',
            'is_collection' => false,
            'is_transfer' => false,
            'rest_request_parameter' => 'required',
            'is_nullable' => false,
        ],
    ];

    /**
     * @return string|null
     */
    public function getAttribute5(): ?string
    {
        return $this->attribute5;
    }

    /**
     * @param string $attribute5
     *
     * @return $this
     */
    public function setAttribute5(string $attribute5)
    {
        $this->attribute5 = $attribute5;
        $this->modifiedProperties[self::ATTRIBUTE5] = true;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttribute6(): ?string
    {
        return $this->attribute6;
    }

    /**
     * @param string $attribute6
     *
     * @return $this
     */
    public function setAttribute6(string $attribute6)
    {
        $this->attribute6 = $attribute6;
        $this->modifiedProperties[self::ATTRIBUTE6] = true;

        return $this;
    }
}
