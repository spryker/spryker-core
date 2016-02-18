<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class BundleUsesConnector implements ViolationFinderInterface
{

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function isViolation(array $dependency)
    {
        return (preg_match('/Connector/', $dependency[DependencyTree::META_FOREIGN_BUNDLE]));
    }

}
