<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Transfer\Log\Processor\Fixtures;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class SimpleTransfer extends AbstractTransfer implements TransferInterface
{
    /**
     * @var array
     */
    protected $array = ['foo' => 'bar'];

    /**
     * @var string
     */
    protected $string = 'baz';

    /**
     * @var string
     */
    protected $notSanitized = 'baz';

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
        'string' => [
            'type' => 'string',
            'name_underscore' => 'string',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        'notSanitized' => [
            'type' => 'string',
            'name_underscore' => 'not_sanitized',
            'is_collection' => false,
            'is_transfer' => false,
        ],
    ];

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->array;
    }

    /**
     * @param array $array
     *
     * @return $this
     */
    public function setArray(array $array)
    {
        $this->array = $array;

        return $this;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @param string $string
     *
     * @return $this
     */
    public function setString(string $string)
    {
        $this->string = $string;

        return $this;
    }

    /**
     * @return string
     */
    public function getNotSanitized(): string
    {
        return $this->notSanitized;
    }

    /**
     * @param string $notSanitized
     *
     * @return $this
     */
    public function setNotSanitized(string $notSanitized)
    {
        $this->notSanitized = $notSanitized;

        return $this;
    }
}
