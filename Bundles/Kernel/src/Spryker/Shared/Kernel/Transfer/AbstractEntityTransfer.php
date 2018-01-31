<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Transfer;

class AbstractEntityTransfer extends AbstractTransfer implements EntityTransferInterface
{
    /**
     * @var null|string
     */
    protected $entityNamespace = null;

    /**
     * Returns FQCN of propel entity it's mapped
     *
     * @return string
     */
    public function _getEntityNamespace()
    {
        return $this->entityNamespace;
    }
}
