<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\DynamicEntityBackendApi\Transfer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class DynamicEntityAttributes extends AbstractTransfer
{
    /**
     * @var array<string, mixed>
     */
    protected $virtualProperties = [];

    /**
     * @param array<string, mixed> $data
     * @param bool $ignoreMissingProperty
     *
     * @return $this
     */
    public function fromArray(array $data, $ignoreMissingProperty = false) // phpcs:ignore
    {
        foreach ($data as $property => $value) {
            $this->virtualProperties[$property] = $value;
        }

        return $this;
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function toArray($isRecursive = true, $camelCasedKeys = false) // phpcs:ignore
    {
        return $this->virtualProperties;
    }
}
