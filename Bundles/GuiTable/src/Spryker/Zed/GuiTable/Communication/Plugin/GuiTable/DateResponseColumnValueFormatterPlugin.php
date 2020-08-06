<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Plugin\GuiTable;

use Spryker\Zed\GuiTable\Communication\ConfigurationProvider\AbstractGuiTableConfigurationProvider;
use Spryker\Zed\GuiTableExtension\Dependency\Plugin\ResponseColumnValueFormatterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GuiTable\GuiTableConfig getConfig()
 * @method \Spryker\Zed\GuiTable\Communication\GuiTableCommunicationFactory getFactory()
 * @method \Spryker\Zed\GuiTable\Business\GuiTableFacadeInterface getFacade()
 */
class DateResponseColumnValueFormatterPlugin extends AbstractPlugin implements ResponseColumnValueFormatterPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getColumnType(): string
    {
        return AbstractGuiTableConfigurationProvider::COLUMN_TYPE_DATE;
    }

    /**
     * {@inheritDoc}
     * - Formats dates to ISO8601 format.
     *
     * @api
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function formatColumnValue($value)
    {
        return $value ? $this->getFactory()->getUtilDateTimeService()->formatDateTimeToIso8601($value) : null;
    }
}
