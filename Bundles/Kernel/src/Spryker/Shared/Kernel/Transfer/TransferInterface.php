<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Transfer;

interface TransferInterface
{
    /**
     * @param bool $isRecursive
     *
     * @return array
     */
    public function toArray(bool $isRecursive = true): array;

    /**
     * @param bool $isRecursive
     *
     * @return array
     */
    public function modifiedToArray(bool $isRecursive = true): array;

    /**
     * @param array $values
     * @param bool $fuzzyMatch
     *
     * @return $this
     */
    public function fromArray(array $values, bool $fuzzyMatch = false);

    /**
     * @param string $propertyName
     *
     * @return bool
     */
    public function isPropertyModified(string $propertyName): bool;
}
