<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Propel;

use Psr\Log\LoggerInterface;

interface PropelAbstractClassValidatorInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $messenger
     * @param null|string $module
     *
     * @return bool
     */
    public function validate(LoggerInterface $messenger, ?string $module): bool;
}
