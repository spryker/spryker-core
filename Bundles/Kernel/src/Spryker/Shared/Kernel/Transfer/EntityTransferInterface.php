<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Transfer;

interface EntityTransferInterface extends TransferInterface
{
    /**
     * @internal
     *
     * Returns FQCN of propel entity it's mapped
     *
     * @return string
     */
    public function entityNamespace();

    /**
     * @return array
     */
    public function getVirtualColumns();
}
