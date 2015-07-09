<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Business\Operation;

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
