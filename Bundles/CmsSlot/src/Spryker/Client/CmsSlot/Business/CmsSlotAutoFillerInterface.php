<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot\Business;

interface CmsSlotAutoFillerInterface
{
    /**
     * @param string[] $fillingKeys
     *
     * @return array
     */
    public function fetchCmsSlotAutoFilled(array $fillingKeys): array;
}
