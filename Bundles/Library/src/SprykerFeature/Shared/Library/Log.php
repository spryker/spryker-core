<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library;

class Log
{

    /**
     * @param string $expression
     * @param string $fileName
     * @param bool|true $showInfo
     * @param string $dir
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
     */
    public static function logRaw($expression, $fileName, $showInfo = true, $dir = 'logs')
    {
        $string = '';
        if ($showInfo) {
            $string = '-------' . PHP_EOL;
            $string .= date('c', time()) . ' ';
        }

        $string .= print_r($expression, true);
        $string .= PHP_EOL;

        $filePath = self::getFilePath($fileName, $dir);

        file_put_contents($filePath, $string, FILE_APPEND);
    }

    /**
     * Writes the pure content of expression into the file
     * - without any overhead
     * - if file already exists, it will be truncated first
     *
     * @static
     *
     * @param $expression
     * @param string $fileName
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
     * @static
     *
     * @param string $fileName
     *
     * @return string
     * @static
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
            if (false === is_dir($logPath)) {
                mkdir($logPath, 0777, true);
            }
        }

        if (!is_writable($logPath)) {
            throw new \Exception(sprintf('Log file "%s" is not writable!', $logPath));
        }

        return $logPath . DIRECTORY_SEPARATOR . $fileName;
    }

}
