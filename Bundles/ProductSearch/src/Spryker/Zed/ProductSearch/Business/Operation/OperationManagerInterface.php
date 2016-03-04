<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Operation;

interface OperationManagerInterface
{

    /**
     * @param int $idAttribute
     * @param string $copyTarget
     *
     * @return bool
     */
    public function hasAttributeOperation($idAttribute, $copyTarget);

    /**
     * @param int $idAttribute
     * @param string $copyTarget
     * @param string $operation
     * @param int $weight
     *
     * @return array
     */
    public function createAttributeOperation($idAttribute, $copyTarget, $operation, $weight);

}
