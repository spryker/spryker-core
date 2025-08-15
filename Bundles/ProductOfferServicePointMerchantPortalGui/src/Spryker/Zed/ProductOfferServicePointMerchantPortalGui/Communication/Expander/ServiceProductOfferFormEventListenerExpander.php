<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\DataProvider\ServiceDataProviderInterface;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\Validator\ServiceValidatorInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Callback;

class ServiceProductOfferFormEventListenerExpander implements ServiceProductOfferFormEventListenerExpanderInterface
{
    /**
     * @uses \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormExpander::FIELD_SERVICE_POINT
     *
     * @var string
     */
    protected const FIELD_SERVICE_POINT = ServicePointTransfer::ID_SERVICE_POINT;

    /**
     * @uses \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Expander\ServiceProductOfferFormExpander::FIELD_SERVICE
     *
     * @var string
     */
    protected const FIELD_SERVICE = ProductOfferTransfer::SERVICES;

    /**
     * @var \Symfony\Component\Form\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\ServiceTransfer>|null, array<int, string>|null>
     */
    protected DataTransformerInterface $serviceDataTransformer;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\DataProvider\ServiceDataProviderInterface
     */
    protected ServiceDataProviderInterface $serviceDataProvider;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\Validator\ServiceValidatorInterface
     */
    protected ServiceValidatorInterface $serviceValidator;

    /**
     * @param \Symfony\Component\Form\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\ServiceTransfer>|null, array<int, string>|null> $serviceDataTransformer
     * @param \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\DataProvider\ServiceDataProviderInterface $serviceDataProvider
     * @param \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Form\Validator\ServiceValidatorInterface $serviceValidator
     */
    public function __construct(
        DataTransformerInterface $serviceDataTransformer,
        ServiceDataProviderInterface $serviceDataProvider,
        ServiceValidatorInterface $serviceValidator
    ) {
        $this->serviceDataTransformer = $serviceDataTransformer;
        $this->serviceDataProvider = $serviceDataProvider;
        $this->serviceValidator = $serviceValidator;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    public function expand(FormBuilderInterface $builder): FormBuilderInterface
    {
        $builder = $this->addServicePointPreSubmitEventListener($builder);
        $builder = $this->addServicePointPostSubmitEventListener($builder);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    protected function addServicePointPreSubmitEventListener(FormBuilderInterface $builder): FormBuilderInterface
    {
        $builder->get(static::FIELD_SERVICE_POINT)->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent) use ($builder): void {
            $idServicePoint = $formEvent->getData();

            if (!$idServicePoint) {
                return;
            }

            $this->addServicePointChoices($formEvent);
            $this->addServiceValidation($formEvent, $builder);
        });

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return void
     */
    protected function addServicePointChoices(FormEvent $formEvent): void
    {
        $form = $formEvent->getForm();
        $idServicePoint = $formEvent->getData();

        $options = $form->getConfig()->getOptions();
        $options['data'] = $idServicePoint;
        $options['choices'] = $this->serviceDataProvider->getServicePointChoicesByIdServicePoint($idServicePoint);

        /** @var \Symfony\Component\Form\FormInterface<mixed> $parentForm */
        $parentForm = $form->getParent();
        $parentForm->add(static::FIELD_SERVICE_POINT, ChoiceType::class, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $formEvent
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return void
     */
    protected function addServiceValidation(FormEvent $formEvent, FormBuilderInterface $builder): void
    {
        $form = $formEvent->getForm();
        /** @var \Symfony\Component\Form\FormInterface<mixed> $parentForm */
        $parentForm = $form->getParent();

        $serviceFieldOptions = $parentForm->get(static::FIELD_SERVICE)->getConfig()->getOptions();
        $serviceFieldOptions['constraints'] = [
            new Callback(['callback' => [$this->serviceValidator, 'validate']]),
        ];

        $parentForm->add($builder->create(static::FIELD_SERVICE, ChoiceType::class, $serviceFieldOptions)
            ->addModelTransformer($this->serviceDataTransformer)
            ->getForm());
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    protected function addServicePointPostSubmitEventListener(FormBuilderInterface $builder): FormBuilderInterface
    {
        $builder->get(static::FIELD_SERVICE_POINT)->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($builder): void {
                $idServicePoint = $event->getData();

                if (!$idServicePoint) {
                    return;
                }

                /** @var \Symfony\Component\Form\FormInterface<mixed> $parentForm */
                $parentForm = $event->getForm()->getParent();
                $serviceFieldOptions = $parentForm->get(static::FIELD_SERVICE)->getConfig()->getOptions();
                $serviceFieldOptions['choices'] = $this->serviceDataProvider->getServiceChoicesByIdServicePoint($idServicePoint);

                $parentForm->add($builder->create(static::FIELD_SERVICE, ChoiceType::class, $serviceFieldOptions)
                    ->addModelTransformer($this->serviceDataTransformer)
                    ->getForm());
            },
        );

        return $builder;
    }
}
