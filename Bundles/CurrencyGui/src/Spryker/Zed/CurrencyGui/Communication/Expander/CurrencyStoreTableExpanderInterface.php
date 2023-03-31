<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication\Expander;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface CurrencyStoreTableExpanderInterface
{
    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfiguration(TableConfiguration $config): TableConfiguration;

    /**
     * @param string $urlPath
     *
     * @return string
     */
    public function expandUrlPath(string $urlPath): string;

    /**
     * @param array<string> $row
     *
     * @return array<string>
     */
    public function expandRow(array $row): array;
}
