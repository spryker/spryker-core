<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Validator\ConstraintProvider;

interface PriceProductConstraintProviderInterface
{
    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    public function getConstraints(): array;
}
