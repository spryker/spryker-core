<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer;
use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ServicePoint\Business\Expander\CountryExpanderInterface;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointExpanderInterface;
use Spryker\Zed\ServicePoint\Business\Filter\ServicePointAddressFilterInterface;
use Spryker\Zed\ServicePoint\Business\Validator\ServicePointAddressValidatorInterface;
use Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface;

class ServicePointAddressCreator implements ServicePointAddressCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface
     */
    protected ServicePointEntityManagerInterface $servicePointEntityManager;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Filter\ServicePointAddressFilterInterface
     */
    protected ServicePointAddressFilterInterface $servicePointAddressFilter;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Validator\ServicePointAddressValidatorInterface
     */
    protected ServicePointAddressValidatorInterface $servicePointAddressValidator;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Expander\CountryExpanderInterface
     */
    protected CountryExpanderInterface $countryExpander;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Expander\ServicePointExpanderInterface
     */
    protected ServicePointExpanderInterface $servicePointExpander;

    /**
     * @param \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface $servicePointEntityManager
     * @param \Spryker\Zed\ServicePoint\Business\Filter\ServicePointAddressFilterInterface $servicePointAddressFilter
     * @param \Spryker\Zed\ServicePoint\Business\Validator\ServicePointAddressValidatorInterface $servicePointAddressValidator
     * @param \Spryker\Zed\ServicePoint\Business\Expander\CountryExpanderInterface $countryExpander
     * @param \Spryker\Zed\ServicePoint\Business\Expander\ServicePointExpanderInterface $servicePointExpander
     */
    public function __construct(
        ServicePointEntityManagerInterface $servicePointEntityManager,
        ServicePointAddressFilterInterface $servicePointAddressFilter,
        ServicePointAddressValidatorInterface $servicePointAddressValidator,
        CountryExpanderInterface $countryExpander,
        ServicePointExpanderInterface $servicePointExpander
    ) {
        $this->servicePointEntityManager = $servicePointEntityManager;
        $this->servicePointAddressFilter = $servicePointAddressFilter;
        $this->servicePointAddressValidator = $servicePointAddressValidator;
        $this->countryExpander = $countryExpander;
        $this->servicePointExpander = $servicePointExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer
     */
    public function createServicePointAddressCollection(
        ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer
    ): ServicePointAddressCollectionResponseTransfer {
        $this->assertRequiredFields($servicePointAddressCollectionRequestTransfer);

        $servicePointAddressCollectionResponseTransfer = (new ServicePointAddressCollectionResponseTransfer())
            ->setServicePointAddresses($servicePointAddressCollectionRequestTransfer->getServicePointAddresses());

        $servicePointAddressCollectionResponseTransfer = $this->servicePointAddressValidator->validate($servicePointAddressCollectionResponseTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $servicePointAddressCollectionResponseTransfer->getErrors();

        if ($servicePointAddressCollectionRequestTransfer->getIsTransactional() && $errorTransfers->count()) {
            return $servicePointAddressCollectionResponseTransfer;
        }

        [$validServicePointAddressTransfers, $invalidServicePointAddressTransfers] = $this->servicePointAddressFilter
            ->filterServicePointAddressesByValidity($servicePointAddressCollectionResponseTransfer);

        if ($validServicePointAddressTransfers->count()) {
            $validServicePointAddressTransfers = $this->expandServicePointAddressTransfers($validServicePointAddressTransfers);
            $validServicePointAddressTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($validServicePointAddressTransfers) {
                return $this->executeCreateServicePointAddressCollectionTransaction($validServicePointAddressTransfers);
            });
        }

        return $servicePointAddressCollectionResponseTransfer->setServicePointAddresses(
            $this->servicePointAddressFilter->mergeServicePointAddresses($validServicePointAddressTransfers, $invalidServicePointAddressTransfers),
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $servicePointAddressTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer>
     */
    protected function executeCreateServicePointAddressCollectionTransaction(
        ArrayObject $servicePointAddressTransfers
    ): ArrayObject {
        $persistedServicePointAddressTransfers = new ArrayObject();

        foreach ($servicePointAddressTransfers as $entityIdentifier => $servicePointAddressTransfer) {
            $servicePointAddressTransfer = $this->servicePointEntityManager->createServicePointAddress($servicePointAddressTransfer);
            $persistedServicePointAddressTransfers->offsetSet($entityIdentifier, $servicePointAddressTransfer);
        }

        return $persistedServicePointAddressTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(ServicePointAddressCollectionRequestTransfer $servicePointAddressCollectionRequestTransfer): void
    {
        $servicePointAddressCollectionRequestTransfer
            ->requireIsTransactional()
            ->requireServicePointAddresses();

        foreach ($servicePointAddressCollectionRequestTransfer->getServicePointAddresses() as $servicePointAddressTransfer) {
            $servicePointAddressTransfer
                ->requireAddress1()
                ->requireAddress2()
                ->requireCity()
                ->requireZipCode();

            $servicePointAddressTransfer->getServicePointOrFail()->requireUuid();
            $servicePointAddressTransfer->getCountryOrFail()->requireIso2Code();

            if ($servicePointAddressTransfer->getRegion()) {
                $servicePointAddressTransfer->getRegionOrFail()->requireUuid();
            }
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $servicePointAddressTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer>
     */
    protected function expandServicePointAddressTransfers(
        ArrayObject $servicePointAddressTransfers
    ): ArrayObject {
        $servicePointAddressCollectionTransfer = (new ServicePointAddressCollectionTransfer())->setServicePointAddresses($servicePointAddressTransfers);
        $servicePointAddressCollectionTransfer = $this->countryExpander
            ->expandServicePointAddressCollectionWithCountriesAndRegions($servicePointAddressCollectionTransfer);
        $servicePointAddressCollectionTransfer = $this->servicePointExpander
            ->expandServicePointAddressCollectionWithServicePointIds($servicePointAddressCollectionTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $servicePointAddressTransfers */
        $servicePointAddressTransfers = $servicePointAddressCollectionTransfer->getServicePointAddresses();

        return $servicePointAddressTransfers;
    }
}
