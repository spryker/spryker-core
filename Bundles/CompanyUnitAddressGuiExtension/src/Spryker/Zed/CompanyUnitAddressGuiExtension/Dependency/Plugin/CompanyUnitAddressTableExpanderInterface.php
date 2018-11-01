<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface CompanyUnitAddressTableExpanderInterface
{
    /**
     * Specification:
     * - Expands array of header columns
     *
     * @api
     *
     * @return array
     */
    public function expandHeader(): array;

    /**
     * Specification:
     * - Expands table config
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration;

    /**
     * Specification:
     * - Expands table row data
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array;
}
