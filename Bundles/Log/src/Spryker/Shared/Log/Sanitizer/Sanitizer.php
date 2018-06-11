<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Sanitizer;

class Sanitizer implements SanitizerInterface
{
    /**
     * @var array
     */
    protected $sanitizeKeys;

    /**
     * @var string
     */
    protected $sanitizedValue;

    /**
     * @param array $sanitizeKeys
     * @param string $sanitizedValue
     */
    public function __construct(array $sanitizeKeys, $sanitizedValue)
    {
        $this->sanitizeKeys = $sanitizeKeys;
        $this->sanitizedValue = $sanitizedValue;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data)
    {
        foreach ($data as $key => $value) {
            $this->sanitizeIfAssociativeArray($data, $key, $value);
            $this->sanitizeIfIndexedArray($data, $key, $value);
            $this->sanitizeIfMatch($data, $key, $value);
        }

        return $data;
    }

    /**
     * @param array $data
     * @param string $key
     * @param string|array $value
     *
     * @return void
     */
    protected function sanitizeIfAssociativeArray(array &$data, $key, $value)
    {
        if (is_array($value) && !$this->isIndexed($value)) {
            $data[$key] = $this->sanitize($value);
        }
    }

    /**
     * @param array $data
     * @param string $key
     * @param string|array $value
     *
     * @return void
     */
    protected function sanitizeIfIndexedArray(array &$data, $key, $value)
    {
        if (is_array($value) && $this->isIndexed($value)) {
            $data[$key] = $this->sanitizeIndexed($value);
        }
    }

    /**
     * @param array $data
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    protected function sanitizeIfMatch(array &$data, $key, $value)
    {
        if ($this->matches($key)) {
            $data[$key] = $this->sanitizeValue($value, $key);
        }
    }

    /**
     * Indexed array can contain arrays, iterate over them and sanitize
     *
     * @param array $data
     *
     * @return array
     */
    protected function sanitizeIndexed(array $data)
    {
        foreach ($data as $position => $innerData) {
            if (is_array($innerData)) {
                $data[$position] = $this->sanitize($innerData);
            }
        }

        return $data;
    }

    /**
     * @param mixed $value
     * @param string $key
     *
     * @return mixed
     */
    public function sanitizeValue($value, $key)
    {
        if ($this->matches($key)) {
            return $this->sanitizedValue;
        }

        return $value;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function matches($key)
    {
        return (in_array($key, $this->sanitizeKeys, true));
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    protected function isIndexed(array $data)
    {
        return (array_values($data) === $data);
    }
}
