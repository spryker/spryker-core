<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGuiExtension\Dependency\Plugin;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface ProductListTableConfigExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands table configuration
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration;
}
