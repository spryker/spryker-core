<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;

class DynamicEntityComprehensiveValidator implements DynamicEntityComprehensiveValidatorInterface
{
    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    protected DynamicEntityValidatorInterface $dynamicEntityValidator;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidatorInterface
     */
    protected DynamicEntityConfigurationValidatorInterface $dynamicEntityConfigurationValidator;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface
     */
    protected DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidatorInterface $dynamicEntityConfigurationValidator
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface $dynamicEntityValidator
     */
    public function __construct(
        DynamicEntityConfigurationValidatorInterface $dynamicEntityConfigurationValidator,
        DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator,
        DynamicEntityValidatorInterface $dynamicEntityValidator
    ) {
        $this->dynamicEntityValidator = $dynamicEntityValidator;
        $this->dynamicEntityConfigurationValidator = $dynamicEntityConfigurationValidator;
        $this->dynamicEntityConfigurationTreeValidator = $dynamicEntityConfigurationTreeValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param int $index
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validateDynamicEntity(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        int $index
    ): array {
        return $this->dynamicEntityValidator->validate(
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $index,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function validateDynamicEntityConfiguration(
        DynamicEntityConfigurationCollectionResponseTransfer $dynamicEntityConfigurationCollectionResponseTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer {
        return $this->dynamicEntityConfigurationValidator->validate($dynamicEntityConfigurationCollectionResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    public function validateDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer,
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
    ): ?ErrorTransfer {
        return $this->dynamicEntityConfigurationTreeValidator->validateDynamicEntityConfigurationCollection(
            $dynamicEntityConfigurationCollectionTransfer,
            $dynamicEntityCriteriaTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer|null
     */
    public function validateDynamicEntityCollectionRequestByDynamicEntityConfigurationCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): ?ErrorTransfer {
        return $this->dynamicEntityConfigurationTreeValidator->validateDynamicEntityCollectionRequestByDynamicEntityConfigurationCollection(
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityConfigurationCollectionTransfer,
        );
    }
}
