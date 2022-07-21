<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\DataProvider;

interface CmsPageConditionDataProviderInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;
}
