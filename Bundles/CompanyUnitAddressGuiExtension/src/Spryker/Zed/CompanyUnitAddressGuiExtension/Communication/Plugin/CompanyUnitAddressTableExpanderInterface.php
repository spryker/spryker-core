<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGuiExtension\Communication\Plugin;

use Orm\Zed\CompanyUnitAddress\Persistence\Map\SpyCompanyUnitAddressTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface CompanyUnitAddressTableExpanderInterface
{
    public const ID_COMPANY_UNIT_ADDRESS = SpyCompanyUnitAddressTableMap::COL_ID_COMPANY_UNIT_ADDRESS;

    /**
     * @return array
     */
    public function expandHeader(): array;

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration;

    /**
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array;
}
