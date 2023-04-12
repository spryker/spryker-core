<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataSet;

interface ServicePointStoreDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_SERVICE_POINT_KEY = 'service_point_key';

    /**
     * @var string
     */
    public const COLUMN_STORE_NAME = 'store_name';

    /**
     * @var string
     */
    public const COLUMN_ID_SERVICE_POINT = 'id_service_point';

    /**
     * @var string
     */
    public const COLUMN_ID_STORE = 'id_store';
}
