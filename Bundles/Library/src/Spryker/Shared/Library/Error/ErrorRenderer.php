<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Error;

use Exception;
use Spryker\Shared\Library\Application\Version;
use Spryker\Shared\Library\Exception\AbstractErrorRendererException;
use Spryker\Zed\Library\Sanitize\Html;

/**
 * @deprecated Use ErrorHandler bundle instead.
 */
class ErrorRenderer
{

    const SAPI_CLI = 'cli';

    /**
     * @param \Exception|\Throwable $e
     *
     * @return string
     */
    protected static function renderForWeb($e)
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'n/a';

        $errorString = '<div style="font-family: courier; font-size: 14px">';
        $message = get_class($e) . ' - ' . $e->getMessage();
        $errorString .= '<h1>' . APPLICATION . ' Exception</h1><div style="background: #dadada; padding: 5px"><font style="12"><b>' . $message . '</b></font></div><br/>';

        $errorString .= 'in ' . $e->getFile() . ' (' . $e->getLine() . ')';
        $errorString .= '<br/><br/>';
        $errorString .= '<b>Url:</b> ' . Html::escape($uri);
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
            $errorString .= '<br/><hr/><br/>' . (string)$e->getExtra();
        }

        return $errorString;
    }

    /**
     * @param \Exception|\Throwable $e
     *
     * @return string
     */
    protected static function renderForCli($e)
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
            $errorString .= PHP_EOL . PHP_EOL . (string)$e->getExtra();
        }

        return $errorString;
    }

    /**
     * @param \Exception|\Throwable $e
     *
     * @return string
     */
    public static function renderException($e)
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
