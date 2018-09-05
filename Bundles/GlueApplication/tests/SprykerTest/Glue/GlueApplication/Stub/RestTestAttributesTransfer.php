<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Stub;

use SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer;

class RestTestAttributesTransfer extends AbstractTransfer
{
    const ATTRIBUTE1 = 'attribute1';

    const ATTRIBUTE2 = 'attribute2';

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
        ],
        self::ATTRIBUTE2 => [
            'type' => 'string',
            'name_underscore' => 'attribute2',
            'is_collection' => false,
            'is_transfer' => false,
        ],
    ];

    /**
     * @return string
     */
    public function getAttribute1(): ?string
    {
        return $this->attribute1;
    }

    /**
     * @param string $attribute1
     *
     * @return \SprykerTest\Glue\GlueApplication\Stub\RestTestAttributesTransfer
     */
    public function setAttribute1(string $attribute1): self
    {
        $this->attribute1 = $attribute1;
        $this->modifiedProperties[self::ATTRIBUTE1] = true;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttribute2(): ?string
    {
        return $this->attribute2;
    }

    /**
     * @param string $attribute2
     *
     * @return \SprykerTest\Glue\GlueApplication\Stub\RestTestAttributesTransfer
     */
    public function setAttribute2(string $attribute2): self
    {
        $this->attribute2 = $attribute2;
        $this->modifiedProperties[self::ATTRIBUTE2] = true;

        return $this;
    }
}
