<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
class SprykerFeature_Zed_Library_Setup
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
     * @static
     *
     * @param array $directories
     */
    public static function checkDirectories(array $directories)
    {
        $checks = [];
        foreach ($directories as $directory) {
            $checks[] = \SprykerFeature_Zed_Library_Setup::checkCondition('is_dir', $directory);
            $checks[] = \SprykerFeature_Zed_Library_Setup::checkCondition('is_writable', $directory);
        }
        foreach ($checks as $check) {
            if ($check === false) {
                \SprykerFeature_Zed_Library_Setup::renderAndExit(\SprykerFeature_Zed_Library_Setup::getErrorMessagesAsList());
            }
        }
    }

    /**
     * creates the given directories unless they exist
     *
     * @param array $directories
     */
    public static function setupDirectories(array $directories)
    {
        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
        }
    }

    public static function checkDirectoriesByName($root, $pattern)
    {
        $dirHelper = new \SprykerFeature_Zed_Library_Helper_Directory();
        $directories = $dirHelper->getDirs($root);

        $entitiesDirectories = [];
        foreach ($directories as $directory) {
            $directory = str_replace('\\', '/', $directory);
            if (strpos($directory, '/' . $pattern . '/') !== false) {
                $entitiesDirectories[] = $directory;
            }
        }
        self::checkDirectories($entitiesDirectories);
    }

    public static function checkCondition($callBack, $value)
    {
        assert(function_exists($callBack));
        $success = $callBack($value);
        if ($success) {
            self::$successMessages[] = "OK - {$callBack} - {$value}";
        } else {
            self::$errorMessages[] = "ERROR - {$callBack} - {$value}";
        }

        return $success;
    }

    public static function getErrorMessagesAsList()
    {
        return '<h1>Enviroment failed</h1><ul><li>' . implode('</li><li>', self::$errorMessages) . '</li></ul>';
    }

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
