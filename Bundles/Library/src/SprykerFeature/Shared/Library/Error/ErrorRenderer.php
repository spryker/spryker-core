<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Error;

use SprykerFeature\Shared\Library\Application\Version;

class ErrorRenderer
{

    /**
     * @param \Exception $e
     *
     * @return string
     */
    protected static function renderForWeb(\Exception $e)
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'n/a';

        $string = '<div style="font-family: courier; font-size: 14px">';
        $message = get_class($e) . ' - ' . $e->getMessage();
        $string .= '<h1>' . APPLICATION . ' Exception</h1><div style="background: #dadada; padding: 5px"><font style="12"><b>' . $message . '</b></font></div><br/>';

        $string .= 'in ' . $e->getFile() . ' (' . $e->getLine() . ')';
        $string .= '<br/><br/>';
        $string .= '<b>Url:</b> ' . $uri;
        $string .= '<br/><br/>';
        $string .= '<b>Trace:</b>';
        $string .= '<br/>';
        $string .= '<pre>' . $e->getTraceAsString() . '</pre>';
        $string .= '</div>';

        $version = new Version();
        if ($version->hasData()) {
            $string .= '<hr>';
            $string .= 'DeployInfo (Revision: ' . $version->getRevision() . ', Path: ' . $version->getPath() . ', Date: ' . $version->getDate() . ')';
        }

        return '<pre>' . $string . '</pre>';
    }

    /**
     * @param \Exception $e
     *
     * @return string
     */
    protected static function renderForCli(\Exception $e)
    {

        if(isset($_SERVER['argv']) && is_array($_SERVER['argv'])){
            $uri = implode(' ', $_SERVER['argv']);
        }else{
            $uri = 'n/a';
        }

        $message = get_class($e) . ' - ' . $e->getMessage();
        $string = PHP_EOL . APPLICATION . ' Exception: ' . $message . PHP_EOL;

        $string .= 'in ' . $e->getFile() . ' (' . $e->getLine() . ')';
        $string .= PHP_EOL . PHP_EOL;
        $string .= 'Cli: ' . $uri;
        $string .= PHP_EOL . PHP_EOL;
        $string .= 'Trace:' . PHP_EOL;
        $string .= $e->getTraceAsString() . PHP_EOL;

        $version = new Version();
        if ($version->hasData()) {
            $string .= 'DeployInfo (Revision: ' . $version->getRevision() . ', Path: ' . $version->getPath() . ', Date: ' . $version->getDate() . ')' . PHP_EOL;
        }

        return $string;
    }

    /**
     * @param \Exception $e
     *
     * @return string
     */
    public static function renderException(\Exception $e)
    {
        if (self::isCliCall()) {
            return self::renderForCli($e);
        }

        return self::renderForWeb($e);
    }

    /**
     * @return bool
     */
    protected static function isCliCall()
    {
        return defined('IS_CLI') && IS_CLI === true;
    }

}
