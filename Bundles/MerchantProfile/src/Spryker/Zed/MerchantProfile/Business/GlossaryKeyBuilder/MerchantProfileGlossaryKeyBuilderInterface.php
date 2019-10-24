<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\GlossaryKeyBuilder;

interface MerchantProfileGlossaryKeyBuilderInterface
{
    /**
     * @param int $fkMerchant
     * @param string $merchantProfileGlossaryAttributeName
     *
     * @return string
     */
    public function buildGlossaryKey(int $fkMerchant, string $merchantProfileGlossaryAttributeName): string;
}
