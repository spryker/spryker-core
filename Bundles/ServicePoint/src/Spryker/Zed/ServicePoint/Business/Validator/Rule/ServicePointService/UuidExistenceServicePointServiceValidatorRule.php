<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointService;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ServicePointServiceConditionsTransfer;
use Generated\Shared\Transfer\ServicePointServiceCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointServiceTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface;

class UuidExistenceServicePointServiceValidatorRule implements ServicePointServiceValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_ENTITY_NOT_FOUND = 'service_point.validation.service_point_service_entity_not_found';

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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $servicePointServiceTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $servicePointServiceTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        foreach ($servicePointServiceTransfers as $entityIdentifier => $servicePointServiceTransfer) {
            if (!$this->hasServicePointServiceWithUuid($servicePointServiceTransfer)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_SERVICE_ENTITY_NOT_FOUND,
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    public function isTerminated(
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        return $postValidationErrorTransfers->count() > $initialErrorTransfers->count();
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointServiceTransfer $servicePointServiceTransfer
     *
     * @return bool
     */
    protected function hasServicePointServiceWithUuid(ServicePointServiceTransfer $servicePointServiceTransfer): bool
    {
        $servicePointServiceConditionsTransfer = (new ServicePointServiceConditionsTransfer())
            ->addUuid($servicePointServiceTransfer->getUuidOrFail());

        $servicePointServiceCriteriaTransfer = (new ServicePointServiceCriteriaTransfer())
            ->setServicePointServiceConditions($servicePointServiceConditionsTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $servicePointServiceTransfers */
        $servicePointServiceTransfers = $this->servicePointRepository
            ->getServicePointServiceCollection($servicePointServiceCriteriaTransfer)
            ->getServicePointServices();

        return $servicePointServiceTransfers->count() === 1;
    }
}
