<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Error;

use SprykerFeature\Shared\Library\Application\Version;
use SprykerFeature\Shared\Library\Exception\AbstractErrorRendererException;

class ErrorRenderer
{

    const SAPI_CLI = 'cli';

    /**
     * @param \Exception $e
     *
     * @return string
     */
    protected static function renderForWeb(\Exception $e)
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'n/a';

        $errorString = '<div style="font-family: courier; font-size: 14px">';
        $message = get_class($e) . ' - ' . $e->getMessage();
        $errorString .= '<h1>' . APPLICATION . ' Exception</h1><div style="background: #dadada; padding: 5px"><font style="12"><b>' . $message . '</b></font></div><br/>';

        $errorString .= 'in ' . $e->getFile() . ' (' . $e->getLine() . ')';
        $errorString .= '<br/><br/>';
        $errorString .= '<b>Url:</b> ' . $uri;
        $errorString .= '<br/><br/>';
        $errorString .= '<b>Trace:</b>';
        $errorString .= '<br/>';
        $errorString .= '<pre>' . $e->getTraceAsString() . '</pre>';
        $errorString .= '</div>';

        $version = new Version();
        if ($version->hasData()) {
            $errorString .= '<hr>';
            $errorString .= 'DeployInfo (Revision: ' . $version->getRevision() . ', Path: ' . $version->getPath() . ', Date: ' . $version->getDate() . ')';
        }

        $errorString = '<pre>' . $errorString . '</pre>';

        if ($e instanceof AbstractErrorRendererException) {
            $errorString .= '<br/><hr/><br/>' . (string) $e->getExtra();
        }

        return $errorString;
    }

    /**
     * @param \Exception $e
     *
     * @return string
     */
    protected static function renderForCli(\Exception $e)
    {
        if (isset($_SERVER['argv']) && is_array($_SERVER['argv'])) {
            $uri = implode(' ', $_SERVER['argv']);
        } else {
            $uri = 'n/a';
        }

        $message = get_class($e) . ' - ' . $e->getMessage();
        $errorString = PHP_EOL . APPLICATION . ' Exception: ' . $message . PHP_EOL;

        $errorString .= 'in ' . $e->getFile() . ' (' . $e->getLine() . ')';
        $errorString .= PHP_EOL . PHP_EOL;
        $errorString .= 'Command: ' . $uri;
        $errorString .= PHP_EOL . PHP_EOL;
        $errorString .= 'Trace:' . PHP_EOL;
        $errorString .= $e->getTraceAsString() . PHP_EOL;

        $version = new Version();
        if ($version->hasData()) {
            $errorString .= 'DeployInfo (Revision: ' . $version->getRevision() . ', Path: ' . $version->getPath() . ', Date: ' . $version->getDate() . ')' . PHP_EOL;
        }

        if ($e instanceof AbstractErrorRendererException) {
            $errorString .= PHP_EOL . PHP_EOL . (string) $e->getExtra();
        }

        return $errorString;
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
        return PHP_SAPI === self::SAPI_CLI;
    }

}
