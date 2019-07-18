<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Collector\Business\Fixture;

use Spryker\Zed\Collector\CollectorConfig;

class CollectorConfigWithNotDefinedDbEngineFake extends CollectorConfig
{
    public const COLLECTOR_BULK_DELETE_QUERY_CLASS = 'WrongBulkDeleteTouchByIdQuery';
    public const COLLECTOR_BULK_UPDATE_QUERY_CLASS = 'WrongBulkUpdateTouchKeyByIdQuery';

    /**
     * @return string
     */
    public function getCurrentEngineName()
    {
        return $this->getMysqlEngineName();
    }
}
