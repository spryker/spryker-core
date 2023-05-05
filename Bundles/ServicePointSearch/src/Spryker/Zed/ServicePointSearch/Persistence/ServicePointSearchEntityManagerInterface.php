<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Persistence;

use Generated\Shared\Transfer\ServicePointSearchTransfer;

interface ServicePointSearchEntityManagerInterface
{
    /**
     * @param list<int> $servicePointIds
     *
     * @return void
     */
    public function deleteServicePointSearchByServicePointIds(array $servicePointIds): void;

    /**
     * @param list<int> $servicePointSearchIds
     *
     * @return void
     */
    public function deleteServicePointSearchByServicePointSearchIds(array $servicePointSearchIds): void;

    /**
     * @param \Generated\Shared\Transfer\ServicePointSearchTransfer $servicePointSearchTransfer
     *
     * @return void
     */
    public function saveServicePointSearch(ServicePointSearchTransfer $servicePointSearchTransfer): void;
}
