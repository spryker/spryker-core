<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder;

interface PathPatternValidatorInterface
{
    /**
     * @param string[] $pathPatterns
     *
     * @return void
     */
    public function validatePathPatterns(array $pathPatterns): void;
}
