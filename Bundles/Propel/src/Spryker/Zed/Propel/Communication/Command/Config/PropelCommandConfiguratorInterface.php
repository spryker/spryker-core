<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Config;

use Spryker\Zed\PropelOrm\Communication\Generator\ConfigurablePropelCommandInterface;

interface PropelCommandConfiguratorInterface
{
    /**
     * @param \Spryker\Zed\PropelOrm\Communication\Generator\ConfigurablePropelCommandInterface $configurablePropelCommand
     *
     * @return void
     */
    public function configurePropelCommand(ConfigurablePropelCommandInterface $configurablePropelCommand): void;
}
