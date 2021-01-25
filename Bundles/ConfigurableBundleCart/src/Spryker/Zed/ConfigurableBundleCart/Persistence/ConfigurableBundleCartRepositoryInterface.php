<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Persistence;

interface ConfigurableBundleCartRepositoryInterface
{
    /**
     * @param string $configurableBundleTemplateUuid
     * @param string[] $configurableBundleTemplateSlotUuids
     *
     * @return bool
     */
    public function verifyConfigurableBundleTemplateSlots(string $configurableBundleTemplateUuid, array $configurableBundleTemplateSlotUuids): bool;
}
