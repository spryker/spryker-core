<?php

namespace SprykerEngine\Shared\Transfer;

interface TransferInterface
{

    /**
     * @param bool $recursive
     *
     * @return array
     */
    public function toArray($recursive = true);

    /**
     * @param bool $recursive
     *
     * @return array
     */
    public function modifiedToArray($recursive = true);

    /**
     * @param array $values
     *
     * @param bool $fuzzyMatch
     */
    public function fromArray(array $values, $fuzzyMatch = false);
}
