<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelGui\Communication\Plugin;

use Spryker\Zed\CompanyUnitAddressGuiExtension\Communication\Plugin\CompanyUnitAddressTableExpanderInterface;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class CompanyUnitAddressTableExpanderPlugin extends AbstractPlugin implements CompanyUnitAddressTableExpanderInterface
{
    const COL_COMPANY_UNIT_ADDRESS_LABELS = 'Labels';

    /**
     * @return array
     */
    public function expandHeader(): array
    {
        return [static::COL_COMPANY_UNIT_ADDRESS_LABELS => static::COL_COMPANY_UNIT_ADDRESS_LABELS];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        $config->addRawColumn(static::COL_COMPANY_UNIT_ADDRESS_LABELS);

        return $config;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        return [static::COL_COMPANY_UNIT_ADDRESS_LABELS => "Label 1, Label 2"];
    }
}
