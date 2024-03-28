<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;

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
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param string|null $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function validate(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        ?string $errorPath = null
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($this->validators as $validator) {
            $dynamicEntityCollectionResponseTransfer = $validator->validate(
                $dynamicEntityCollectionRequestTransfer,
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityCollectionResponseTransfer,
                $errorPath,
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }
}
