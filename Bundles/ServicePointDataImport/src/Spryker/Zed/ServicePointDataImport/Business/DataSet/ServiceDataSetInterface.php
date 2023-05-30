<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataSet;

interface ServiceDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_KEY = 'key';

    /**
     * @var string
     */
    public const COLUMN_SERVICE_POINT_KEY = 'service_point_key';

    /**
     * @var string
     */
    public const COLUMN_SERVICE_TYPE_KEY = 'service_type_key';

    /**
     * @var string
     */
    public const COLUMN_IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    public const COLUMN_ID_SERVICE_POINT = 'id_service_point';

    /**
     * @var string
     */
    public const COLUMN_ID_SERVICE_TYPE = 'id_service_type';
}
