<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Builder;

interface OptionsBuilderInterface
{
    /**
     * @return array<mixed>
     */
    public function buildOptions(): array;
}
