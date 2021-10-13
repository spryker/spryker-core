<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Gui\Communication\Fixture;

class DownloadTableWithoutGetDownloadQueryMethod extends FooTable
{
    /**
     * @return array<string>
     */
    protected function getCsvHeaders(): array
    {
        return [
            'db_column_1' => 'Header column 1',
            'db_column_2' => 'Header column 2',
        ];
    }
}
