<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CollectorSearchConnector\Dependency\Facade;

interface CollectorSearchConnectorToCollectorInterface
{
    /**
     * @param array $timestamps
     *
     * @return void
     */
    public function deleteSearchTimestamps(array $timestamps = []);
}
