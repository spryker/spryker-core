<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyTreeWriter;

interface DependencyTreeWriterInterface
{
    /**
     * @param array $dependencyTree
     *
     * @return void
     */
    public function write(array $dependencyTree);
}
