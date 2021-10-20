<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelGui\Communication\Plugin;

use Generated\Shared\Transfer\SpyCompanyUnitAddressLabelEntityTransfer;
use Orm\Zed\CompanyUnitAddress\Persistence\Map\SpyCompanyUnitAddressTableMap;
use Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableConfigExpanderPluginInterface;
use Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableDataExpanderPluginInterface;
use Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableHeaderExpanderPluginInterface;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabelGui\Communication\CompanyUnitAddressLabelGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyUnitAddressLabelGui\CompanyUnitAddressLabelGuiConfig getConfig()
 */
class CompanyUnitAddressTableExpanderPlugin extends AbstractPlugin implements
    CompanyUnitAddressTableConfigExpanderPluginInterface,
    CompanyUnitAddressTableHeaderExpanderPluginInterface,
    CompanyUnitAddressTableDataExpanderPluginInterface
{
    protected const ID_COMPANY_UNIT_ADDRESS = SpyCompanyUnitAddressTableMap::COL_ID_COMPANY_UNIT_ADDRESS;

    /**
     * @var string
     */
    protected const COL_COMPANY_UNIT_ADDRESS_LABELS = 'Labels';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function expandHeader(): array
    {
        return [static::COL_COMPANY_UNIT_ADDRESS_LABELS => static::COL_COMPANY_UNIT_ADDRESS_LABELS];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        return [static::COL_COMPANY_UNIT_ADDRESS_LABELS => $this->getLabels($item)];
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getLabels(array $item)
    {
        $labelCollection = $this->getFactory()
            ->getCompanyUnitAddressLabelFacade()
            ->getCompanyUnitAddressLabelsByAddress($item[static::ID_COMPANY_UNIT_ADDRESS]);

        $labels = (array)$labelCollection->getLabels();

        return implode(
            '',
            array_map(
                function (SpyCompanyUnitAddressLabelEntityTransfer $item) {
                    return $this->beautifyLabel($item->getName());
                },
                $labels
            )
        );
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function beautifyLabel(string $name): string
    {
        return "<span class='company-unit-address-label'>$name</span>";
    }
}
