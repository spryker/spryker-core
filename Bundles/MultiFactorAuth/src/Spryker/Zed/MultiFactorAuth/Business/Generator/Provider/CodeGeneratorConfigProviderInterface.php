<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Generator\Provider;

interface CodeGeneratorConfigProviderInterface
{
    /**
     * @return int
     */
    public function getCodeLength(): int;

    /**
     * @return int
     */
    public function getCodeValidityTtl(): int;
}
