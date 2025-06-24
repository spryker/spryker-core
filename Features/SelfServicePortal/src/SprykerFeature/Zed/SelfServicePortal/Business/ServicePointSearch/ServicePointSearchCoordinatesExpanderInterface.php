<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\ServicePointSearch;

use Generated\Shared\Transfer\ServicePointTransfer;

interface ServicePointSearchCoordinatesExpanderInterface
{
    /**
     * @param array<string, mixed> $searchData
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return array<string, mixed>
     */
    public function expandWithCoordinates(array $searchData, ServicePointTransfer $servicePointTransfer): array;
}
