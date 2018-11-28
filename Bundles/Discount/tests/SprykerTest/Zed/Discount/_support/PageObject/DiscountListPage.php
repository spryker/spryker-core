<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\PageObject;

class DiscountListPage
{
    public const URL = '/discount/index/list';

    public const SELECTOR_DATA_TABLE = '.dataTables_wrapper';

    public const DATA_TABLE_DATA = 'table.dataTable>tbody';

    public const DATA_TABLE_ROW = 'table.dataTable>tbody>tr';
}
