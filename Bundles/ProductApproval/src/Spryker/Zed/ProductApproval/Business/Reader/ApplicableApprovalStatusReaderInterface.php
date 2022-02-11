<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Reader;

interface ApplicableApprovalStatusReaderInterface
{
    /**
     * @param string $currentStatus
     *
     * @return array<string>
     */
    public function getApplicableApprovalStatuses(string $currentStatus): array;
}
