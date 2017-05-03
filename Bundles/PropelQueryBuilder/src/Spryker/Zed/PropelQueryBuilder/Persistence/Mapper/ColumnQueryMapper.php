<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\Mapper;

use Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderTableTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class ColumnQueryMapper implements ColumnQueryMapperInterface
{

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer $queryBuilderTableTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer[] $selectedColumns
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapColumns(
        ModelCriteria $query,
        PropelQueryBuilderTableTransfer $queryBuilderTableTransfer,
        array $selectedColumns = []
    ) {
        $query = $this->mapSelectedColumns($query, $queryBuilderTableTransfer, $selectedColumns);

        return $query;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer $queryBuilderTableTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer[] $selectedColumns
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function mapSelectedColumns(ModelCriteria $query, PropelQueryBuilderTableTransfer $queryBuilderTableTransfer, array $selectedColumns)
    {
        $selectedColumns = $this->getSelectedColumns($queryBuilderTableTransfer, $selectedColumns);

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
     * @param \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer $queryBuilderTableTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer[] $selectedColumns
     *
     * @return array
     */
    protected function getSelectedColumns(PropelQueryBuilderTableTransfer $queryBuilderTableTransfer, array $selectedColumns)
    {
        $columns = [];
        $tableColumns = (array)$queryBuilderTableTransfer->getColumns();

        if (!$selectedColumns) {
            return $this->selectAllColumns($tableColumns);
        }

        foreach ($tableColumns as $tableColumnTransfer) {
            foreach ($selectedColumns as $selectedColumnTransfer) {
                $this->assertSelectedColumns($selectedColumnTransfer, $tableColumnTransfer);

                if (mb_strtolower($tableColumnTransfer->getName()) === mb_strtolower($selectedColumnTransfer->getName())) {
                    $columns[] = $selectedColumnTransfer->getAlias();
                }
            }
        }

        return $columns;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer[] $tableColumns
     *
     * @return array
     */
    protected function selectAllColumns($tableColumns)
    {
        $columns = [];
        foreach ($tableColumns as $tableColumnTransfer) {
            $columns[] = $tableColumnTransfer->getAlias();
        }

        return $columns;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer $selectedColumnTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer $tableColumnTransfer
     *
     * @return void
     */
    protected function assertSelectedColumns(
        PropelQueryBuilderColumnTransfer $selectedColumnTransfer,
        PropelQueryBuilderColumnTransfer $tableColumnTransfer
    ) {
        $selectedColumnTransfer->requireName();
        $selectedColumnTransfer->requireAlias();

        $tableColumnTransfer->requireName();
    }

}
