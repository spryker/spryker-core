<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Transfer\Log\Processor\Fixtures;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class ComplexTransfer extends AbstractTransfer implements TransferInterface
{
    /**
     * @var array
     */
    protected $array = ['foo', 'bar'];

    /**
     * @var \ArrayObject
     */
    protected $emptyTransfer;

    /**
     * @var \SprykerTest\Shared\Transfer\Log\Processor\Fixtures\SimpleTransfer
     */
    protected $innerTransfer;

    /**
     * @var \SprykerTest\Shared\Transfer\Log\Processor\Fixtures\SimpleTransfer[]
     */
    protected $transferCollection;

    /**
     * @var array
     */
    protected $transferMetadata = [
        'array' => [
            'type' => 'array',
            'name_underscore' => 'array',
            'is_collection' => true,
            'is_transfer' => false,
        ],
        'emptyTransfer' => [
            'type' => '\SprykerTest\Shared\Transfer\Log\Processor\Fixtures\SimpleTransfer',
            'name_underscore' => 'empty_transfer',
            'is_collection' => false,
            'is_transfer' => true,
        ],
        'innerTransfer' => [
            'type' => '\SprykerTest\Shared\Transfer\Log\Processor\Fixtures\SimpleTransfer',
            'name_underscore' => 'inner_transfer',
            'is_collection' => false,
            'is_transfer' => true,
        ],
        'transferCollection' => [
            'type' => '\SprykerTest\Shared\Transfer\Log\Processor\Fixtures\SimpleTransfer',
            'name_underscore' => 'transfer_collection',
            'is_collection' => true,
            'is_transfer' => true,
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->emptyTransfer = new ArrayObject();
        $this->innerTransfer = new SimpleTransfer();
        $this->transferCollection = [
            new SimpleTransfer(),
        ];
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->array;
    }

    /**
     * @param array $array
     *
     * @return $this
     */
    public function setArray($array)
    {
        $this->array = $array;

        return $this;
    }

    /**
     * @return \ArrayObject
     */
    public function getEmptyTransfer()
    {
        return $this->emptyTransfer;
    }

    /**
     * @param \ArrayObject $emptyTransfer
     *
     * @return $this
     */
    public function setEmptyTransfer($emptyTransfer)
    {
        $this->emptyTransfer = $emptyTransfer;

        return $this;
    }

    /**
     * @return \SprykerTest\Shared\Transfer\Log\Processor\Fixtures\SimpleTransfer
     */
    public function getInnerTransfer()
    {
        return $this->innerTransfer;
    }

    /**
     * @param \SprykerTest\Shared\Transfer\Log\Processor\Fixtures\SimpleTransfer $innerTransfer
     *
     * @return $this
     */
    public function setInnerTransfer($innerTransfer)
    {
        $this->innerTransfer = $innerTransfer;

        return $this;
    }

    /**
     * @return \SprykerTest\Shared\Transfer\Log\Processor\Fixtures\SimpleTransfer[]
     */
    public function getTransferCollection()
    {
        return $this->transferCollection;
    }

    /**
     * @param \SprykerTest\Shared\Transfer\Log\Processor\Fixtures\SimpleTransfer[] $transferCollection
     *
     * @return $this
     */
    public function setTransferCollection($transferCollection)
    {
        $this->transferCollection = $transferCollection;

        return $this;
    }
}
