<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\PageObject;

class DiscountListPage
{
    /**
     * @var string
     */
    public const URL = '/discount/index/list';

    /**
     * @var string
     */
    public const SELECTOR_DATA_TABLE = '.dataTables_wrapper';

    /**
     * @var string
     */
    public const DATA_TABLE_DATA = 'table.dataTable>tbody';

    /**
     * @var string
     */
    public const DATA_TABLE_ROW = 'table.dataTable>tbody>tr';
}
