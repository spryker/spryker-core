<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyTreeWriter;

interface DependencyTreeWriterInterface
{

    /**
     * @param array $dependencyTree
     *
     * @return mixed
     */
    public function write(array $dependencyTree);

}
