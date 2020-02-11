<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotDataImport\Dependency\Service;

interface CmsSlotDataImportToUtilTextServiceInterface
{
    /**
     * @param string $value
     * @param string $algorithm
     *
     * @return string
     */
    public function hashValue($value, $algorithm);
}
