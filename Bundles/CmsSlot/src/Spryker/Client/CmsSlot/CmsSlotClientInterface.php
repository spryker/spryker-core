<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot;

interface CmsSlotClientInterface
{
    /**
     * Specification:
     * - Fetches external data for CMS slot by provided keys.
     *
     * @api
     *
     * @param string[] $dataKeys
     *
     * @return array
     */
    public function getCmsSlotExternalDataByKeys(array $dataKeys): array;
}
