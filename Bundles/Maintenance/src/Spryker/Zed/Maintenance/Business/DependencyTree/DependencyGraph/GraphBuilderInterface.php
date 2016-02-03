<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
