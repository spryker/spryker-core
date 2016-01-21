<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader;

abstract class AbstractDependencyTreeReader
{

    /**
     * @return array
     */
    abstract public function read();

}
