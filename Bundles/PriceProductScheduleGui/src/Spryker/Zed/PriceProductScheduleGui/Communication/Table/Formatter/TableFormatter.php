<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table\Formatter;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;

class TableFormatter implements TableFormatterInterface
{
    /**
     * @param \Spryker\Zed\Gui\Communication\Table\AbstractTable $table
     *
     * @return array
     */
    public function formatAbstractTableToArray(AbstractTable $table): array
    {
        $tableData = $table->fetchData();
        $tableData['header'] = $table->getConfiguration()->getHeader();

        return $tableData;
    }
}
