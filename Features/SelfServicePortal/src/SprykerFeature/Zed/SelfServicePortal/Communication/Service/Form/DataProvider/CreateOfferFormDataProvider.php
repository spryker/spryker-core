<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\CreateOfferForm;

class CreateOfferFormDataProvider
{
    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected StoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface
     */
    protected ShipmentTypeFacadeInterface $shipmentTypeFacade;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface
     */
    protected ServicePointFacadeInterface $servicePointFacade;

    /**
     * @var array<string, \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\DataTransformerInterface<mixed, mixed>>
     */
    protected array $formDataTransformers;

    /**
     * @var list<\Symfony\Component\EventDispatcher\EventSubscriberInterface>
     */
    protected array $formEventSubscribers;

    /**
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface $shipmentTypeFacade
     * @param \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface $servicePointFacade
     * @param array<string, \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\DataTransformerInterface<mixed, mixed>> $formDataTransformers
     * @param list<\Symfony\Component\EventDispatcher\EventSubscriberInterface> $formEventSubscribers
     */
    public function __construct(
        StoreFacadeInterface $storeFacade,
        ShipmentTypeFacadeInterface $shipmentTypeFacade,
        ServicePointFacadeInterface $servicePointFacade,
        array $formDataTransformers,
        array $formEventSubscribers
    ) {
        $this->storeFacade = $storeFacade;
        $this->shipmentTypeFacade = $shipmentTypeFacade;
        $this->servicePointFacade = $servicePointFacade;
        $this->formDataTransformers = $formDataTransformers;
        $this->formEventSubscribers = $formEventSubscribers;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $storeChoices = $this->prepareStoreChoices($this->storeFacade->getAllStores());
        $servicePointChoices = $this->prepareServicePointChoices($this->servicePointFacade->getServicePointCollection(
            (new ServicePointCriteriaTransfer())
                ->setServicePointConditions(
                    (new ServicePointConditionsTransfer()),
                ),
        ));
        $shipmentTypeChoices = $this->prepareShipmentTypeChoices($this->shipmentTypeFacade->getShipmentTypeCollection(
            (new ShipmentTypeCriteriaTransfer())
                ->setShipmentTypeConditions(
                    (new ShipmentTypeConditionsTransfer()),
                ),
        ));
        $servicePointServiceChoices = $this->prepareServicePointServiceChoices($this->servicePointFacade->getServiceCollection(
            (new ServiceCriteriaTransfer())
                ->setServiceConditions(
                    (new ServiceConditionsTransfer()),
                ),
        ));

        /** @var array<string, mixed> $options */
        $options = [
            CreateOfferForm::OPTION_STORE_CHOICES => $storeChoices,
            CreateOfferForm::OPTION_SHIPMENT_TYPE_CHOICES => $shipmentTypeChoices,
            CreateOfferForm::OPTION_SERVICE_POINT_CHOICES => $servicePointChoices,
            CreateOfferForm::OPTION_SERVICE_POINT_SERVICE_CHOICES => $servicePointServiceChoices,
            CreateOfferForm::OPTION_FORM_DATA_TRANSFORMERS => $this->formDataTransformers,
            CreateOfferForm::OPTION_FORM_EVENT_SUBSCRIBERS => $this->formEventSubscribers,
        ];

        return $options;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function getData(ProductConcreteTransfer $productConcreteTransfer): ProductOfferTransfer
    {
        return (new ProductOfferTransfer())
            ->setConcreteSku($productConcreteTransfer->getSkuOrFail())
            ->setIdProductConcrete($productConcreteTransfer->getIdProductConcreteOrFail())
            ->setProductOfferValidity(
                new ProductOfferValidityTransfer(),
            );
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<string, string>
     */
    protected function prepareStoreChoices(array $storeTransfers): array
    {
        $storeChoices = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeChoices[$storeTransfer->getNameOrFail()] = (string)$storeTransfer->getIdStoreOrFail();
        }

        return $storeChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function prepareShipmentTypeChoices(ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer): array
    {
        $shipmentTypeChoices = [];
        foreach ($shipmentTypeCollectionTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $activityString = $shipmentTypeTransfer->getIsActive() ? 'Active' : 'Inactive';
            $shipmentTypeKey = sprintf('%s (%s)', $shipmentTypeTransfer->getNameOrFail(), $activityString);
            $shipmentTypeChoices[$shipmentTypeKey] = (string)$shipmentTypeTransfer->getUuidOrFail();
        }

        return $shipmentTypeChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointCollectionTransfer $servicePointCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function prepareServicePointChoices(ServicePointCollectionTransfer $servicePointCollectionTransfer): array
    {
        $servicePointChoices = [];
        foreach ($servicePointCollectionTransfer->getServicePoints() as $servicePointTransfer) {
            $activityString = $servicePointTransfer->getIsActive() ? 'Active' : 'Inactive';
            $servicePointKey = sprintf('%s (%s)', $servicePointTransfer->getNameOrFail(), $activityString);
            $servicePointChoices[$servicePointKey] = (string)$servicePointTransfer->getIdServicePointOrFail();
        }

        return $servicePointChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionTransfer $serviceCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function prepareServicePointServiceChoices(ServiceCollectionTransfer $serviceCollectionTransfer): array
    {
        $servicePointServiceChoices = [];
        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            $servicePointServiceChoices[$serviceTransfer->getUuidOrFail()] = $serviceTransfer->getUuidOrFail();
        }

        return $servicePointServiceChoices;
    }
}
