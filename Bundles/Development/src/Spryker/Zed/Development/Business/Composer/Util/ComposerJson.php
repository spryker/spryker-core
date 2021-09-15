<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Util;

use RuntimeException;

class ComposerJson
{
    /**
     * @var string[]
     */
    protected static $objectKeys = [
        'autoload',
        'autoload-dev',
        'require',
        'require-dev',
        'config',
        'scripts',
    ];

    /**
     * @param string $file
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public static function fromFile(string $file): array
    {
        $content = file_get_contents($file);
        if ($content === false) {
            throw new RuntimeException('Cannot decode composer.json file');
        }

        return static::fromString($content);
    }

    /**
     * @param string $string
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public static function fromString(string $string): array
    {
        $array = json_decode($string, true);
        if (!$array) {
            throw new RuntimeException('Cannot decode composer.json content');
        }

        return $array;
    }

    /**
     * @param array $array
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public static function toString(array $array): string
    {
        $objectKeys = static::$objectKeys;
        foreach ($objectKeys as $objectKey) {
            if (!isset($array[$objectKey]) || !empty($array[$objectKey])) {
                continue;
            }

            $array[$objectKey] = (object)$array[$objectKey];
        }

        $json = json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new RuntimeException('Cannot encode fixed composer.json file');
        }

        return $json . PHP_EOL;
    }

    /**
     * @param string $file
     * @param array $array
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public static function toFile(string $file, array $array): void
    {
        $json = static::toString($array);

        $result = file_put_contents($file, $json);
        if ($result === false) {
            throw new RuntimeException('Cannot write to composer.json file');
        }
    }
}
