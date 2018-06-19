<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Config;

class Profiler
{
    const PROFILE_VALUE = 'value';
    const PROFILE_DEFAULT = 'default';
    const PROFILE_COUNT = 'count';

    /**
     * @var array
     */
    protected $profileData = [];

    /**
     * @param string $key
     * @param mixed|null $default
     * @param mixed|null $value
     *
     * @return void
     */
    public function add($key, $default, $value)
    {
        if (!isset($this->profileData[$key])) {
            $this->createProfileEntry($key, $default, $value);

            return;
        }

        $this->updateProfileEntry($key);
    }

    /**
     * @return array
     */
    public function getProfileData()
    {
        return $this->profileData;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @param mixed|null $value
     *
     * @return void
     */
    protected function createProfileEntry($key, $default, $value)
    {
        $this->profileData[$key] = [
            static::PROFILE_VALUE => $this->formatValue($value),
            static::PROFILE_DEFAULT => $this->formatValue($default),
            static::PROFILE_COUNT => 1,
        ];
    }

    /**
     * @param mixed $value
     *
     * @return string|array
     */
    protected function formatValue($value)
    {
        if (is_object($value)) {
            return get_class($value);
        }

        if (is_bool($value)) {
            return ($value) ? 'true' : 'false';
        }

        if (is_array($value)) {
            if (count($value) === 0) {
                return '[]';
            }

            foreach ($value as $key => $data) {
                $value[$key] = $this->formatValue($data);
            }

            return $value;
        }

        return $value;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function updateProfileEntry($key)
    {
        $this->profileData[$key][static::PROFILE_COUNT]++;
    }
}
