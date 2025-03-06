<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Form\Expander;

use SprykerFeature\Shared\SspInquiryManagement\SspInquiryManagementConfig;
use SprykerFeature\Yves\SspInquiryManagement\Form\SspInquiryForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateOrderSspInquiryFormExpander implements CreateSspInquiryFormExpanderInterface
{
    /**
     * @var string
     */
    protected const FIELD_ORDER_REFERENCE = 'orderReference';

    /**
     * @var string
     */
    protected const FIELD_TYPE = 'type';

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(protected RequestStack $requestStack)
    {
    }

    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            return false;
        }

        return $request->query->has('orderReference') && !empty($request->query->get('orderReference'));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void
    {
        $this->addOrderReference($builder);
        $this->addTypeField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOrderReference(FormBuilderInterface $builder)
    {
        if (!$this->requestStack->getCurrentRequest()) {
            return $this;
        }

        $builder->add(static::FIELD_ORDER_REFERENCE . '_display', TextType::class, [
            'priority' => 2,
            'label' => 'ssp_inquiry.order_reference.label',
            'constraints' => [
                new NotBlank(),
            ],
            'data' => $this->requestStack->getCurrentRequest()->query->get('orderReference'),
            'disabled' => true,
        ]);

        $builder->add(static::FIELD_ORDER_REFERENCE, HiddenType::class, [
            'data' => $this->requestStack->getCurrentRequest()->query->get('orderReference'),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addTypeField(FormBuilderInterface $builder, array $options)
    {
        $selectableTypes = $options[SspInquiryForm::OPTION_SSP_INQUIRY_TYPE_CHOICES][SspInquiryManagementConfig::ORDER_SSP_INQUIRY_SOURCE] ?? [];

        $mappedTypes = array_combine(array_map(fn ($type) => 'ssp_inquiry.type.' . $type, $selectableTypes), $selectableTypes);

        if (count($selectableTypes) === 1) {
            $builder->add(static::FIELD_TYPE, HiddenType::class, [
                'data' => $selectableTypes[0],
            ]);
        }

        $builder->add(static::FIELD_TYPE . (count($selectableTypes) === 1 ? '_display' : ''), ChoiceType::class, [
            'priority' => 1,
            'choices' => $mappedTypes,
            'required' => true,
            'label' => 'ssp_inquiry.type.label',
            'placeholder' => 'ssp_inquiry.create.select_type',
            'constraints' => [
                new NotBlank(),
            ],
            'attr' => [
                'required' => 'required',
            ],
            'disabled' => count($selectableTypes) === 1,
            'data' => count($selectableTypes) === 1 ? $selectableTypes[0] : null,
        ]);

        return $this;
    }
}
