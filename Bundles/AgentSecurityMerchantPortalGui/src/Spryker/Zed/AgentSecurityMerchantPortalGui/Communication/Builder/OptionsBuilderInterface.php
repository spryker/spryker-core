<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Builder;

interface OptionsBuilderInterface
{
    /**
     * @return array<string, mixed>
     */
    public function buildOptions(): array;
}
