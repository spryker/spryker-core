<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph;

interface GraphBuilderInterface
{

    /**
     * @param array $dependencyTree
     *
     * @return bool
     */
    public function build(array $dependencyTree);

}
