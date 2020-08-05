<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Plugin\GuiTable;

use DateTime;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Spryker\Zed\GuiTable\Communication\ConfigurationProvider\AbstractGuiTableConfigurationProvider;
use Spryker\Zed\GuiTableExtension\Dependency\Plugin\RequestFilterValueNormalizerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GuiTable\GuiTableConfig getConfig()
 * @method \Spryker\Zed\GuiTable\Communication\GuiTableCommunicationFactory getFactory()
 * @method \Spryker\Zed\GuiTable\Business\GuiTableFacadeInterface getFacade()
 */
class DateRangeRequestFilterValueNormalizerPlugin extends AbstractPlugin implements RequestFilterValueNormalizerPluginInterface
{
    protected const FILTER_DATE_TIME_FORMAT = 'Y-m-d\TH:i:s.uP';
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getFilterType(): string
    {
        return AbstractGuiTableConfigurationProvider::FILTER_TYPE_DATE_RANGE;
    }

    /**
     * {@inheritDoc}
     * - Formats filter values to CriteriaRangeFilterTransfer.
     * - Formats values to null if they have inappropriate format.
     *
     * @api
     *
     * @param mixed $value
     *
     * @return \Generated\Shared\Transfer\CriteriaRangeFilterTransfer|null
     */
    public function normalizeFilterValue($value)
    {
        if (!$value || !is_array($value) || (!isset($value['from']) && !isset($value['to']))) {
            return null;
        }

        $fromDate = $value['from'] ?? null;
        if ($fromDate) {
            $fromDate = DateTime::createFromFormat(static::FILTER_DATE_TIME_FORMAT, $fromDate)
                ->format(self::DATE_TIME_FORMAT);
        }

        $toDate = $value['to'] ?? null;
        if ($toDate) {
            $toDate = DateTime::createFromFormat(static::FILTER_DATE_TIME_FORMAT, $toDate)
                ->format(self::DATE_TIME_FORMAT);
        }

        return (new CriteriaRangeFilterTransfer())
            ->setFrom($fromDate)
            ->setTo($toDate);
    }
}
