<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator\Rule\ServiceType;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface;

class NameExistenceServiceTypeValidatorRule implements ServiceTypeValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_EXISTS = 'service_point.validation.service_type_name_exists';

    /**
     * @var \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface
     */
    protected ServicePointRepositoryInterface $servicePointRepository;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface $servicePointRepository
     * @param \Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        ServicePointRepositoryInterface $servicePointRepository,
        ErrorAdderInterface $errorAdder
    ) {
        $this->servicePointRepository = $servicePointRepository;
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer> $serviceTypeTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $serviceTypeTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        foreach ($serviceTypeTransfers as $entityIdentifier => $serviceTypeTransfer) {
            if ($this->hasServiceTypeWithName($serviceTypeTransfer)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_NAME_EXISTS,
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return bool
     */
    protected function hasServiceTypeWithName(ServiceTypeTransfer $serviceTypeTransfer): bool
    {
        $serviceTypeConditionsTransfer = (new ServiceTypeConditionsTransfer())
            ->addName($serviceTypeTransfer->getNameOrFail());

        if ($serviceTypeTransfer->getUuid()) {
            $serviceTypeConditionsTransfer
                ->addUuid($serviceTypeTransfer->getUuidOrFail())
                ->setIsUuidsConditionInversed(true);
        }

        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())
            ->setServiceTypeConditions($serviceTypeConditionsTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer> $serviceTypeTransfers */
        $serviceTypeTransfers = $this->servicePointRepository
            ->getServiceTypeCollection($serviceTypeCriteriaTransfer)
            ->getServiceTypes();

        return $serviceTypeTransfers->count() > 0;
    }
}
