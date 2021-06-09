<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Helper;

use Spryker\Client\Storage\StorageClientInterface;
use SprykerTest\Shared\Testify\Helper\MessageFormatter;

class InMemoryStoragePlugin implements InMemoryStoragePluginInterface
{
    use MessageFormatter;

    /**
     * @var array
     */
    protected static $storage = [];

    /**
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return void
     */
    public function set(string $key, string $value, ?int $ttl = null): void
    {
        static::$storage[$key] = [
            'value' => $value,
            'ttl' => $ttl,
        ];
    }

    /**
     * @param array $items
     *
     * @return void
     */
    public function setMulti(array $items): void
    {
        codecept_debug($this->format(sprintf(
            '<fg=green>%s::setMulti()</> was triggered with: (originally an array for debugging output converted to json)',
            StorageClientInterface::class
        )));

        codecept_debug($this->format(sprintf('<fg=yellow>%s</>', json_encode($items, JSON_PRETTY_PRINT))));

        foreach ($items as $key => $value) {
            if (!is_scalar($value)) {
                $value = json_encode($value);
            }
            $this->set($key, $value);
        }
    }

    /**
     * @param string $key
     *
     * @return int
     */
    public function delete(string $key): int
    {
        unset(static::$storage[$key]);

        return 1;
    }

    /**
     * @param array $keys
     *
     * @return int
     */
    public function deleteMulti(array $keys): int
    {
        codecept_debug($this->format(sprintf(
            '<fg=green>%s::deleteMulti()</> was triggered with: (originally an array for debugging output converted to json)',
            StorageClientInterface::class
        )));

        codecept_debug($this->format(sprintf('<fg=yellow>%s</>', json_encode($keys, JSON_PRETTY_PRINT))));

        foreach ($keys as $key) {
            $this->delete($key);
        }

        return count($keys);
    }

    /**
     * @return int
     */
    public function deleteAll(): int
    {
        static::$storage = [];

        return 1;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        $value = (isset(static::$storage[$key])) ? static::$storage[$key]['value'] : null;

        $result = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
            return $value;
        }

        return $result;
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getMulti(array $keys): array
    {
        $multi = [];

        foreach ($keys as $key) {
            $multi[$key] = $this->get($key);
        }

        return $multi;
    }

    /**
     * @return array
     */
    public function getStats(): array
    {
        codecept_debug('Not implemented yet.');

        return [];
    }

    /**
     * @return array
     */
    public function getAllKeys(): array
    {
        return array_keys(static::$storage);
    }

    /**
     * @param string $pattern
     *
     * @return array
     */
    public function getKeys(string $pattern): array
    {
        codecept_debug('Not implemented yet.');

        return [];
    }

    /**
     * @return void
     */
    public function resetAccessStats(): void
    {
        codecept_debug('Not implemented yet.');
    }

    /**
     * @return array
     */
    public function getAccessStats(): array
    {
        codecept_debug('Not implemented yet.');

        return [];
    }

    /**
     * @return int
     */
    public function getCountItems(): int
    {
        return count(static::$storage);
    }

    /**
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void
    {
        codecept_debug('Not implemented yet.');
    }
}
