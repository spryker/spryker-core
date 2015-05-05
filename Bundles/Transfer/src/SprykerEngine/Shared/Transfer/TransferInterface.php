<?php

namespace SprykerEngine\Shared\Transfer;

interface TransferInterface
{

    /**
     * @return boolean
     */
    public function isEmpty();

    /**
     * @param bool $includeNullValues
     * @param bool $recursive
     * @param bool $formatToUnderscore
     * @return array
     */
    public function toArray($includeNullValues = true, $recursive = true, $formatToUnderscore = true);

    /**
     * @param bool $recursive
     * @param bool $formatToUnderscore
     * @return array
     */
    public function modifiedToArray($recursive = true, $formatToUnderscore = true);

    /**
     * @param array $values
     * @param bool $fuzzyMatch
     */
    public function fromArray(array $values, $fuzzyMatch = false);

    /**
     * @throws \RuntimeException
     */
    public function validate();
}
