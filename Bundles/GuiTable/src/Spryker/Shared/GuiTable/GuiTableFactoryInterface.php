<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable;

use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;

interface GuiTableFactoryInterface
{
    /**
     * @api
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    public function createConfigurationBuilder(): GuiTableConfigurationBuilderInterface;
}
