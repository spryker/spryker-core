<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Transfer;

interface TransferInterface
{

    /**
     * @param bool $isRecursive
     *
     * @return array
     */
    public function toArray($isRecursive = true);

    /**
     * @param bool $isRecursive
     *
     * @return array
     */
    public function modifiedToArray($isRecursive = true);

    /**
     * @param array $values
     * @param bool $fuzzyMatch
     */
    public function fromArray(array $values, $fuzzyMatch = false);

}
