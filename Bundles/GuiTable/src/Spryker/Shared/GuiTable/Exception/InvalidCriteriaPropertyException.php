<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Exception;

use RuntimeException;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class InvalidCriteriaPropertyException extends RuntimeException
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $criteriaTransfer
     * @param string $property
     */
    public function __construct(AbstractTransfer $criteriaTransfer, string $property)
    {
        parent::__construct(sprintf('Criteria is not valid. %s::%s property not found ', get_class($criteriaTransfer), $property));
    }
}
