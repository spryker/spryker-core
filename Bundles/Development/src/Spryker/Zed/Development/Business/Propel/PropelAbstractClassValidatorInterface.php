<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Propel;

use Symfony\Component\Console\Output\OutputInterface;

interface PropelAbstractClassValidatorInterface
{
    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string|null $module
     *
     * @return bool
     */
    public function validate(OutputInterface $output, ?string $module): bool;
}
