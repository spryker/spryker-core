<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Logger;

class PathFinder implements PathFinderInterface
{
    /**
     * @var string
     */
    public const SAPI_CLI = 'cli';

    /**
     * @var string
     */
    public const SAPI_PHPDBG = 'phpdbg';

    /**
     * @var string
     */
    public const DOCUMENT_URI = 'DOCUMENT_URI';

    /**
     * @var string
     */
    public const ARGV = 'argv';

    /**
     * @return string
     */
    public function getCurrentExecutionPath()
    {
        if (PHP_SAPI !== self::SAPI_CLI && PHP_SAPI !== self::SAPI_PHPDBG) {
            return $_SERVER[self::DOCUMENT_URI];
        }

        $path = self::SAPI_CLI;
        if (isset($_SERVER[self::ARGV]) && is_array($_SERVER[self::ARGV])) {
            $path = implode(' ', $_SERVER[self::ARGV]);
        }

        return $path;
    }
}
