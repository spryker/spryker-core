<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Command\Config;

use Spryker\Zed\PropelOrm\Business\Generator\PropelConfigurableInterface;

interface PropelOriginCommandConfigBuilderInterface
{
    /**
     * @param \Spryker\Zed\PropelOrm\Business\Generator\PropelConfigurableInterface $propelCommand
     *
     * @return \Spryker\Zed\PropelOrm\Business\Generator\PropelConfigurableInterface
     */
    public function configureCommand(PropelConfigurableInterface $propelCommand): PropelConfigurableInterface;
}
