<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilGlob\Glob;

use Webmozart\Glob\Glob as WebmozartGlob;

class Glob implements GlobInterface
{
    public function __construct()
    {
        defined('GLOB_BRACE') || define('GLOB_BRACE', 0);
    }

    /**
     * @param string $pattern
     * @param int $flags
     *
     * @return string[]
     */
    public function glob(string $pattern, int $flags = 0): array
    {
        return WebmozartGlob::glob(rtrim($pattern, DIRECTORY_SEPARATOR), $flags);
    }
}
