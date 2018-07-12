<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

interface ExporterInterface
{
    /**
     * @param string[] $resources
     * @param int[] $ids
     *
     * @return void
     */
    public function exportSynchronizedData(array $resources, array $ids = []): void;
}
