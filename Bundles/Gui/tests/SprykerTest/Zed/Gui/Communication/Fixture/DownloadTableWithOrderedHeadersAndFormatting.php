<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Gui\Communication\Fixture;

use ArrayObject;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

class DownloadTableWithOrderedHeadersAndFormatting extends FooTable
{
    /**
     * @return array<string>
     */
    protected function getCsvHeaders(): array
    {
        return [
            'db_column_2' => 'Header column 1',
            'db_column_1' => 'Header column 2',
        ];
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @return array
     */
    protected function formatCsvRow(ActiveRecordInterface $entity): array
    {
        $dataArray = $entity->toArray();
        $dataArray['db_column_1'] = 'Formatted ' . $dataArray['db_column_1'];

        return $dataArray;
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function getDownloadQuery(): ModelCriteria
    {
        return new ModelCriteria();
    }

    /**
     * @return iterable
     */
    protected function executeDownloadQuery(): iterable
    {
        return new ArrayObject([
            new ActiveRecord([
                'db_column_1' => 'Row 1 column 1',
                'db_column_2' => 'Row 1 column 2',
            ]), new ActiveRecord([
                'db_column_1' => 'Row 2 column 1',
                'db_column_2' => 'Row 2 column 2',
            ]),
        ]);
    }
}
