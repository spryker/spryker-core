<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder;

class PathPatternValidator implements PathPatternValidatorInterface
{
    /**
     * @param string[] $pathPatterns
     *
     * @return bool
     */
    public function isValidPathPatterns(array $pathPatterns): bool
    {
        foreach ($pathPatterns as $pathPattern) {
            $directoryCollection = glob($pathPattern);

            if (count($directoryCollection) === 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $pathPattern
     *
     * @return bool
     */
    protected function isPathPatternValid(string $pathPattern): bool
    {
        $directoryCollection = glob($pathPattern);

        return !(count($directoryCollection) === 0);
    }
}
