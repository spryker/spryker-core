<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library;

use Exception;

class Log
{

    /**
     * @param string $expression
     * @param string $fileName
     * @param bool|true $showInfo
     * @param string $dir
     *
     * @return void
     */
    public static function log($expression, $fileName, $showInfo = true, $dir = 'logs')
    {
        if (is_scalar($expression)) {
            $expression = strip_tags($expression);
        }

        self::logRaw($expression, $fileName, $showInfo, $dir);
    }

    /**
     * Simple Logger
     *
     * @param mixed $expression
     * @param string $fileName
     * @param bool $showInfo
     * @param string $dir
     *
     * @return void
     */
    public static function logRaw($expression, $fileName, $showInfo = true, $dir = 'logs')
    {
        $string = '';
        if ($showInfo) {
            $string = '-------' . PHP_EOL;
            $string .= date('c', time()) . ' ';
        }

        $sanitizedExpression = print_r($expression, true);

        $string .= htmlentities($sanitizedExpression, ENT_QUOTES);
        $string .= PHP_EOL;

        $filePath = self::getFilePath($fileName, $dir);

        file_put_contents($filePath, $string, FILE_APPEND);
    }

    /**
     * Writes the pure content of expression into the file
     * - without any overhead
     * - if file already exists, it will be truncated first
     *
     * @param mixed $expression
     * @param string $fileName
     *
     * @return void
     */
    public static function setFlashInFile($expression, $fileName)
    {
        $filePath = self::getFilePath($fileName);
        $string = serialize($expression);
        file_put_contents($filePath, $string);
    }

    /**
     * Retrieves Content from Flashfile
     *
     * @param string $fileName
     *
     * @return string
     */
    public static function getFlashInFile($fileName)
    {
        $filePath = self::getFilePath($fileName);
        if (!file_exists($filePath)) {
            return '';
        }
        $content = file_get_contents($filePath);
        if (empty($content)) {
            return '';
        }

        return unserialize($content);
    }

    /**
     * @param string $fileName
     * @param string $dir
     *
     * @throws \Exception
     *
     * @return string
     */
    public static function getFilePath($fileName, $dir = 'logs')
    {
        $logPath = DataDirectory::getLocalStoreSpecificPath($dir);
        if ($dir === 'logs') {
            $logPath .= APPLICATION . DIRECTORY_SEPARATOR;
            if (is_dir($logPath) === false) {
                mkdir($logPath, 0775, true);
            }
        }

        if (!is_writable($logPath)) {
            throw new Exception(sprintf('Log file "%s" is not writable!', $logPath));
        }

        return $logPath . DIRECTORY_SEPARATOR . $fileName;
    }

}
