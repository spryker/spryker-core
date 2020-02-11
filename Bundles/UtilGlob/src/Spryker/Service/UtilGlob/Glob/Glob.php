<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilGlob\Glob;

use Webmozart\Glob\Glob as WebmozartGlob;

class Glob implements GlobInterface
{
    /**
     * @param string $pattern
     * @param int $flags
     *
     * @return array
     */
    public function glob(string $pattern, int $flags = 0): array
    {
        return WebmozartGlob::glob(rtrim($pattern, DIRECTORY_SEPARATOR), $flags);
    }
}
