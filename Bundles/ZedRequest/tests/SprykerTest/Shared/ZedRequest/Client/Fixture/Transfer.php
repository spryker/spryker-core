<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ZedRequest\Client\Fixture;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class Transfer extends AbstractTransfer
{

    const KEY = 'key';

    /**
     * @var string
     */
    protected $key;

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::KEY => [
            'type' => 'string',
            'name_underscore' => 'key',
            'is_collection' => false,
            'is_transfer' => false,
        ],
    ];

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        $this->addModifiedProperty(self::KEY);

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return $this
     */
    public function requireKey()
    {
        $this->assertPropertyIsSet(self::KEY);

        return $this;
    }

}
