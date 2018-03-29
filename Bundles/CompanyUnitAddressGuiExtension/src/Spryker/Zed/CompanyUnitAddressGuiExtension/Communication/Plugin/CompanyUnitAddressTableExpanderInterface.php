<?php

namespace Spryker\Zed\CompanyUnitAddressGuiExtension\Communication\Plugin;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface CompanyUnitAddressTableExpanderInterface
{
    public function expandHeader(): array;

    public function expandConfig(TableConfiguration $config): TableConfiguration;

    public function expandData(array $item): array;
}
