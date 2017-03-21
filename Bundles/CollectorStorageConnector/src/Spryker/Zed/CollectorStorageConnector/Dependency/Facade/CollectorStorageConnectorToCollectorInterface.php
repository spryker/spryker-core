<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CollectorStorageConnector\Dependency\Facade;

interface CollectorStorageConnectorToCollectorInterface
{

    /**
     * @param array $timestamps
     *
     * @return void
     */
    public function deleteStorageTimestamps(array $timestamps);

}
