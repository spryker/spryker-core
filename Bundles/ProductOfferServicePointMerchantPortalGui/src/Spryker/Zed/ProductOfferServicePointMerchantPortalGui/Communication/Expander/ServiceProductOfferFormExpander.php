<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\DataProvider\ServiceDataProviderInterface;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Dependency\Service\ProductOfferServicePointMerchantPortalGuiToUtilEncodingInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ServiceProductOfferFormExpander implements ServiceProductOfferFormExpanderInterface
{
    /**
     * @var string
     */
    protected const FIELD_SERVICE_POINT = ServicePointTransfer::ID_SERVICE_POINT;

    /**
     * @var string
     */
    protected const FIELD_SERVICE = ProductOfferTransfer::SERVICES;

    /**
     * @var string
     */
    protected const LABEL_SERVICE_POINT = 'Service Point';

    /**
     * @var string
     */
    protected const LABEL_SERVICE = 'Services';

    /**
     * @var string
     */
    protected const PLACEHOLDER_SERVICE_POINT = 'Start typing to search...';

    /**
     * @var string
     */
    protected const DATASOURCE_URL_SERVICE_POINT = '/product-offer-service-point-merchant-portal-gui/service-point-autocomplete?term=${value}';

    /**
     * @var string
     */
    protected const DATASOURCE_URL_SERVICE = '/product-offer-service-point-merchant-portal-gui/service-autocomplete?idServicePoint=${value}';

    /**
     * @var int
     */
    protected const DATASOURCE_MIN_CHARACTERS_SERVICE_POINT = 2;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Dependency\Service\ProductOfferServicePointMerchantPortalGuiToUtilEncodingInterface
     */
    protected ProductOfferServicePointMerchantPortalGuiToUtilEncodingInterface $utilEncodingService;

    /**
     * @var \Symfony\Component\Form\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\ServiceTransfer>|null, array<int, string>|null>
     */
    protected DataTransformerInterface $serviceDataTransformer;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\DataProvider\ServiceDataProviderInterface
     */
    protected ServiceDataProviderInterface $serviceDataProvider;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormEventListenerExpanderInterface
     */
    protected ServiceProductOfferFormEventListenerExpanderInterface $serviceProductOfferFormEventListenerExpander;

    /**
     * @param \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Dependency\Service\ProductOfferServicePointMerchantPortalGuiToUtilEncodingInterface $utilEncodingService
     * @param \Symfony\Component\Form\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\ServiceTransfer>|null, array<int, string>|null> $serviceDataTransformer
     * @param \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\DataProvider\ServiceDataProviderInterface $serviceDataProvider
     * @param \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormEventListenerExpanderInterface $serviceProductOfferFormEventListenerExpander
     */
    public function __construct(
        ProductOfferServicePointMerchantPortalGuiToUtilEncodingInterface $utilEncodingService,
        DataTransformerInterface $serviceDataTransformer,
        ServiceDataProviderInterface $serviceDataProvider,
        ServiceProductOfferFormEventListenerExpanderInterface $serviceProductOfferFormEventListenerExpander
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->serviceDataTransformer = $serviceDataTransformer;
        $this->serviceDataProvider = $serviceDataProvider;
        $this->serviceProductOfferFormEventListenerExpander = $serviceProductOfferFormEventListenerExpander;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder = $this->addServicePointField($builder);
        $builder = $this->addServiceField($builder);

        $builder = $this->serviceProductOfferFormEventListenerExpander->expand($builder);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    protected function addServicePointField(FormBuilderInterface $builder): FormBuilderInterface
    {
        $builder->add(static::FIELD_SERVICE_POINT, ChoiceType::class, [
            'label' => static::LABEL_SERVICE_POINT,
            'attr' => [
                'placeholder' => static::PLACEHOLDER_SERVICE_POINT,
                'search' => true,
                'server-search' => true,
                'datasource' => $this->utilEncodingService->encodeJson([
                    'type' => 'trigger',
                    'event' => 'input',
                    'minCharacters' => static::DATASOURCE_MIN_CHARACTERS_SERVICE_POINT,
                    'datasource' => [
                        'type' => 'http',
                        'url' => static::DATASOURCE_URL_SERVICE_POINT,
                    ],
                ]),
                'dependableId' => 'service',
                'load-options-before-invoking-datasource' => true,
            ],
            'mapped' => false,
            'choices' => $this->serviceDataProvider->getServicePointChoicesByServices($builder->getForm()->getData()->getServices()),
            'required' => false,
            'data' => $this->findServicePointIdInServiceTransfers($builder->getForm()->getData()->getServices()),
        ]);

        $builder->get(static::FIELD_SERVICE_POINT)->resetViewTransformers();

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    protected function addServiceField(FormBuilderInterface $builder): FormBuilderInterface
    {
        $idServicePoint = $this->findServicePointIdInServiceTransfers($builder->getForm()->getData()->getServices());

        $builder->add(static::FIELD_SERVICE, ChoiceType::class, [
            'label' => static::LABEL_SERVICE,
            'attr' => [
                'disabled-when-no-options' => true,
                'datasource' => $this->utilEncodingService->encodeJson([
                    'type' => 'dependable-element',
                    'id' => 'service',
                    'datasource' => [
                        'type' => 'http',
                        'url' => static::DATASOURCE_URL_SERVICE,
                    ],
                ]),
                'load-options-before-invoking-datasource' => true,
            ],
            'choices' => $idServicePoint ? $this->serviceDataProvider->getServiceChoicesByIdServicePoint($idServicePoint) : [],
            'multiple' => true,
            'required' => false,
            'auto_initialize' => false,
        ]);

        $builder->get(static::FIELD_SERVICE)->addModelTransformer($this->serviceDataTransformer);

        return $builder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     *
     * @return int|null
     */
    protected function findServicePointIdInServiceTransfers(ArrayObject $serviceTransfers): ?int
    {
        foreach ($serviceTransfers as $serviceTransfer) {
            return $serviceTransfer->getServicePointOrFail()->getIdServicePointOrFail();
        }

        return null;
    }
}
