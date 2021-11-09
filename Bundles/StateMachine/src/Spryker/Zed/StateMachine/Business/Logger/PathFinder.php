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
        if (PHP_SAPI !== static::SAPI_CLI && PHP_SAPI !== static::SAPI_PHPDBG) {
            return $_SERVER[static::DOCUMENT_URI];
        }

        $path = static::SAPI_CLI;
        if (isset($_SERVER[static::ARGV]) && is_array($_SERVER[static::ARGV])) {
            $path = implode(' ', $_SERVER[static::ARGV]);
        }

        return $path;
    }
}
