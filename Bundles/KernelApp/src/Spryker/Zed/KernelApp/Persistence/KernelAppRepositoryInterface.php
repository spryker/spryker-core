<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Persistence;

interface KernelAppRepositoryInterface
{
    /**
     * @param int $gracePeriod
     *
     * @return list<\Generated\Shared\Transfer\AppConfigTransfer>
     */
    public function getActiveAppConfigs(int $gracePeriod): array;
}
