<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Command\Builder;

use Symfony\Component\Console\Command\Command;

interface PropelOriginCommandBuilderInterface
{
    /**
     * @param string $originPropelCommandClassName
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createOriginCommand(string $originPropelCommandClassName): Command;
}
