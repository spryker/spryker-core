<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator\Rule\Service;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface;

class ServiceTypeExistenceServiceValidatorRule implements ServiceValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_RELATION_ALREADY_EXISTS = 'service_point.validation.service_type_relation_already_exists';

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
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $serviceTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        foreach ($serviceTransfers as $entityIdentifier => $serviceTransfer) {
            if ($this->hasServicePointToServiceTypeRelation($serviceTransfer)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_SERVICE_TYPE_RELATION_ALREADY_EXISTS,
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return bool
     */
    protected function hasServicePointToServiceTypeRelation(
        ServiceTransfer $serviceTransfer
    ): bool {
        $servicePointUuid = $serviceTransfer->getServicePointOrFail()->getUuidOrFail();
        $serviceTypeUuid = $serviceTransfer->getServiceTypeOrFail()->getUuidOrFail();

        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->addServicePointUuid($servicePointUuid)
            ->addServiceTypeUuid($serviceTypeUuid);

        if ($serviceTransfer->getUuid()) {
            $serviceConditionsTransfer
                ->addUuid($serviceTransfer->getUuidOrFail())
                ->setIsUuidsConditionInversed(true);
        }

        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers */
        $serviceTransfers = $this->servicePointRepository
            ->getServiceCollection($serviceCriteriaTransfer)
            ->getServices();

        return $serviceTransfers->count() > 0;
    }
}
