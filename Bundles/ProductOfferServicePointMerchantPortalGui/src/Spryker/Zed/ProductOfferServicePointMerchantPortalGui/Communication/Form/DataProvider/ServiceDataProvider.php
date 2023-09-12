<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointSearchConditionsTransfer;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Dependency\Facade\ProductOfferServicePointMerchantPortalGuiToServicePointFacadeInterface;

class ServiceDataProvider implements ServiceDataProviderInterface
{
    /**
     * @var string
     */
    protected const PATTERN_SELECT_OPTION_TITLE = '%s - %s';

    /**
     * @var \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Dependency\Facade\ProductOfferServicePointMerchantPortalGuiToServicePointFacadeInterface
     */
    protected ProductOfferServicePointMerchantPortalGuiToServicePointFacadeInterface $servicePointFacade;

    /**
     * @param \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Dependency\Facade\ProductOfferServicePointMerchantPortalGuiToServicePointFacadeInterface $servicePointFacade
     */
    public function __construct(ProductOfferServicePointMerchantPortalGuiToServicePointFacadeInterface $servicePointFacade)
    {
        $this->servicePointFacade = $servicePointFacade;
    }

    /**
     * @param string $searchTerm
     *
     * @return list<array<string, mixed>>
     */
    public function getServicePointSelectOptions(string $searchTerm): array
    {
        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())->setServicePointSearchConditions(
            (new ServicePointSearchConditionsTransfer())->setName($searchTerm)->setKey($searchTerm),
        );

        $servicePointCollectionTransfer = $this->servicePointFacade->getServicePointCollection($servicePointCriteriaTransfer);

        $selectOptions = [];
        foreach ($servicePointCollectionTransfer->getServicePoints() as $servicePointTransfer) {
            $selectOptions[] = [
                'title' => $this->getSelectOptionTitle(
                    $servicePointTransfer->getKeyOrFail(),
                    $servicePointTransfer->getNameOrFail(),
                ),
                'value' => $servicePointTransfer->getIdServicePointOrFail(),
            ];
        }

        return $selectOptions;
    }

    /**
     * @param int $idServicePoint
     *
     * @return list<array<string, string>>
     */
    public function getServiceSelectOptions(int $idServicePoint): array
    {
        $serviceCollectionTransfer = $this->getServiceCollectionByIdServicePoint($idServicePoint);

        $selectOptions = [];
        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            $selectOptions[] = [
                'title' => $this->getSelectOptionTitle(
                    $serviceTransfer->getKeyOrFail(),
                    $serviceTransfer->getServiceTypeOrFail()->getNameOrFail(),
                ),
                'value' => $serviceTransfer->getUuidOrFail(),
            ];
        }

        return $selectOptions;
    }

    /**
     * @param int $idServicePoint
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollectionByIdServicePoint(int $idServicePoint): ServiceCollectionTransfer
    {
        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())->setServiceConditions(
            (new ServiceConditionsTransfer())->addIdServicePoint($idServicePoint),
        );

        return $this->servicePointFacade->getServiceCollection($serviceCriteriaTransfer);
    }

    /**
     * @param int $idServicePoint
     *
     * @return array<string, string>
     */
    public function getServiceChoicesByIdServicePoint(int $idServicePoint): array
    {
        $serviceCollectionTransfer = $this->getServiceCollectionByIdServicePoint($idServicePoint);

        $choices = [];
        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            $choices[$this->getSelectOptionTitle(
                $serviceTransfer->getKeyOrFail(),
                $serviceTransfer->getServiceTypeOrFail()->getNameOrFail(),
            )] = $serviceTransfer->getUuidOrFail();
        }

        return $choices;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     *
     * @return array<string, int>
     */
    public function getServicePointChoicesByServices(ArrayObject $serviceTransfers): array
    {
        $choices = [];
        foreach ($serviceTransfers as $serviceTransfer) {
            $servicePointTransfer = $serviceTransfer->getServicePointOrFail();
            $choices[$this->getSelectOptionTitle(
                $servicePointTransfer->getKeyOrFail(),
                $servicePointTransfer->getNameOrFail(),
            )] = $servicePointTransfer->getIdServicePointOrFail();

            break;
        }

        return $choices;
    }

    /**
     * @param int $idServicePoint
     *
     * @return array<string, int>
     */
    public function getServicePointChoicesByIdServicePoint(int $idServicePoint): array
    {
        $servicePointCollectionTransfer = $this->servicePointFacade->getServicePointCollection(
            (new ServicePointCriteriaTransfer())->setServicePointConditions(
                (new ServicePointConditionsTransfer())->addIdServicePoint($idServicePoint),
            ),
        );

        $choices = [];
        foreach ($servicePointCollectionTransfer->getServicePoints() as $servicePointTransfer) {
            $choices[$this->getSelectOptionTitle(
                $servicePointTransfer->getKeyOrFail(),
                $servicePointTransfer->getNameOrFail(),
            )] = $servicePointTransfer->getIdServicePointOrFail();
        }

        return $choices;
    }

    /**
     * @param string $key
     * @param string $name
     *
     * @return string
     */
    protected function getSelectOptionTitle(string $key, string $name): string
    {
        return sprintf(
            static::PATTERN_SELECT_OPTION_TITLE,
            $key,
            $name,
        );
    }
}
