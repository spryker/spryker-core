<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;

class DynamicEntityValidator implements DynamicEntityValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface>
     */
    protected array $validators;

    /**
     * @param array<\Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface> $validators
     */
    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param int $index
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validate(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        int $index
    ): array {
        $errorTransfers = [];

        foreach ($this->validators as $validator) {
            $errorTransfers = array_merge($errorTransfers, $validator->validate(
                $dynamicEntityCollectionRequestTransfer,
                $dynamicEntityTransfer,
                $dynamicEntityConfigurationTransfer,
                $index,
            ));
        }

        return $errorTransfers;
    }
}
