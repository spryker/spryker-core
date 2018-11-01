<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\Mapper;

use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class ColumnQueryMapper implements ColumnQueryMapperInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapColumns(ModelCriteria $query, PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer)
    {
        $this->assertTransferFields($columnSelectionTransfer);

        $query = $this->mapQuery($query, $columnSelectionTransfer);

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer
     *
     * @return void
     */
    protected function assertTransferFields(PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer)
    {
        $columnSelectionTransfer->requireTableColumns();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mapQuery(ModelCriteria $query, PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer)
    {
        $selectedColumns = $this->getSelectedColumns($columnSelectionTransfer);

        return $this->selectQueryColumns($query, $selectedColumns);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param array $selectedColumns
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function selectQueryColumns(ModelCriteria $query, array $selectedColumns)
    {
        $query->clearSelectColumns();
        $query->select($selectedColumns);

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer
     *
     * @return array
     */
    protected function getSelectedColumns(PropelQueryBuilderColumnSelectionTransfer $columnSelectionTransfer)
    {
        $selectedColumns = [];

        if ($columnSelectionTransfer->getSelectedColumns()->count()) {
            foreach ($columnSelectionTransfer->getTableColumns() as $tableColumnTransfer) {
                foreach ($columnSelectionTransfer->getSelectedColumns() as $selectedColumnTransfer) {
                    if (mb_strtolower($tableColumnTransfer->getName()) === mb_strtolower($selectedColumnTransfer->getName())) {
                        $selectedColumns[$selectedColumnTransfer->getName()] = $selectedColumnTransfer->getAlias();
                    }
                }
            }
        } else {
            foreach ($columnSelectionTransfer->getTableColumns() as $columnTransfer) {
                $selectedColumns[$columnTransfer->getName()] = $columnTransfer->getAlias();
            }
        }

        return $selectedColumns;
    }
}
