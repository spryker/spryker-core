<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
class SprykerFeature_Shared_Library_Log
{

    /**
     * Simple Logger - removes html tags
     *
     * @param mixed $expression
     * @param string $fileName
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
        file_put_contents($filePath, $string, LOCK_EX);
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
     * @return string
     * @static
     */
    public static function getFilePath($fileName, $dir = 'logs')
    {
        assert(is_string($fileName));

        $logPath = \SprykerFeature_Shared_Library_Data::getLocalStoreSpecificPath($dir);
        if ($dir === 'logs') {
            $logPath .= APPLICATION . DIRECTORY_SEPARATOR;
            if (false === is_dir($logPath)) {
                mkdir($logPath, 0777, true);
            }
        }

        assert(is_writable($logPath));

        return $logPath . DIRECTORY_SEPARATOR . $fileName;
    }

}
