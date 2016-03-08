<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library;

class Setup
{

    /**
     * @var array
     */
    protected static $successMessages = [];

    /**
     * @var array
     */
    protected static $errorMessages = [];

    /**
     * Checks if the given directories are there and writeable
     *
     * @param array $directories
     *
     * @return void
     */
    public static function checkDirectories(array $directories)
    {
        $checks = [];
        foreach ($directories as $directory) {
            $checks[] = self::checkCondition('is_dir', $directory);
            $checks[] = self::checkCondition('is_writable', $directory);
        }
        foreach ($checks as $check) {
            if ($check === false) {
                self::renderAndExit(self::getErrorMessagesAsList());
            }
        }
    }

    /**
     * creates the given directories unless they exist
     *
     * @param array $directories
     *
     * @return void
     */
    public static function setupDirectories(array $directories)
    {
        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
        }
    }

    /**
     * @param callable|string $callBack
     * @param mixed $value
     *
     * @return mixed
     */
    public static function checkCondition($callBack, $value)
    {
        $success = $callBack($value);
        if ($success) {
            self::$successMessages[] = "OK - {$callBack} - {$value}";
        } else {
            self::$errorMessages[] = "ERROR - {$callBack} - {$value}";
        }

        return $success;
    }

    /**
     * @return string
     */
    public static function getErrorMessagesAsList()
    {
        return '<h1>Enviroment failed</h1><ul><li>' . implode('</li><li>', self::$errorMessages) . '</li></ul>';
    }

    /**
     * @param string $str
     * @param string $background
     * @param string $color
     *
     * @return void
     */
    public static function renderAndExit($str, $background = 'black', $color = 'white')
    {
        if (is_array($str)) {
            $str = '<ul><li>' . implode('</li><li>', $str) . '</li></ul>';
        }

        if (strtolower(PHP_SAPI) !== 'cli') {
            echo "
<html>
<body style='background-color: $background; font-family: courier new; color: $color;'>
<pre>
$str
</pre>
</body>
</html>
";
        } else {
            echo preg_replace('/<br *\/?>/', PHP_EOL, $str);
        }

        exit(0);
    }

}
