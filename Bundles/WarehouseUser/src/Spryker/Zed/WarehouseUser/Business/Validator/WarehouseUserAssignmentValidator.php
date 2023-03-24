<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;

class WarehouseUserAssignmentValidator implements WarehouseUserAssignmentValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentValidatorRuleInterface>
     */
    protected array $warehouseUserAssignmentValidatorRules = [];

    /**
     * @param array<\Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentValidatorRuleInterface> $warehouseUserAssignmentValidatorRules
     */
    public function __construct(array $warehouseUserAssignmentValidatorRules)
    {
        $this->warehouseUserAssignmentValidatorRules = $warehouseUserAssignmentValidatorRules;
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function validateCollection(
        ArrayObject $warehouseUserAssignmentTransfers
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        $warehouseUserAssignmentCollectionResponseTransfer = (new WarehouseUserAssignmentCollectionResponseTransfer())->setWarehouseUserAssignments(
            $warehouseUserAssignmentTransfers,
        );
        foreach ($this->warehouseUserAssignmentValidatorRules as $warehouseUserAssignmentValidatorRule) {
            $warehouseUserAssignmentCollectionResponseTransfer = $warehouseUserAssignmentValidatorRule->validateCollection(
                $warehouseUserAssignmentTransfers,
                $warehouseUserAssignmentCollectionResponseTransfer,
            );
        }

        return $warehouseUserAssignmentCollectionResponseTransfer;
    }
}
