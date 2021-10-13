<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\SchemaFinder;

interface ZedNavigationSchemaFinderInterface
{
    /**
     * @param string $fileNamePattern
     *
     * @return \Symfony\Component\Finder\Finder<\Symfony\Component\Finder\SplFileInfo>
     */
    public function getSchemaFiles(string $fileNamePattern);
}
