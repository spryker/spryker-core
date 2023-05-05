<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeDataImport\Business\DataSet;

interface ShipmentTypeDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_KEY = 'key';

    /**
     * @var string
     */
    public const COLUMN_NAME = 'name';

    /**
     * @var string
     */
    public const COLUMN_IS_ACTIVE = 'is_active';
}
