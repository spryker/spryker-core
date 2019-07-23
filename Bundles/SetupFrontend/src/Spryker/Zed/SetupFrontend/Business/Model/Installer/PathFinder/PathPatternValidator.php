<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder;

use Spryker\Zed\SetupFrontend\Business\Model\Exception\PathPatternInvalidException;

class PathPatternValidator implements PathPatternValidatorInterface
{
    /**
     * @param string[] $pathPatterns
     *
     * @throws \Spryker\Zed\SetupFrontend\Business\Model\Exception\PathPatternInvalidException
     *
     * @return void
     */
    public function validatePathPatterns(array $pathPatterns): void
    {
        foreach ($pathPatterns as $pathPattern) {
            $directoryCollection = glob($pathPattern);

            if (count($directoryCollection) === 0) {
                throw new PathPatternInvalidException(sprintf('Path pattern %s is invalid', $pathPattern));
            }
        }
    }
}
