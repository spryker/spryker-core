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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateGeneralSspInquiryFormExpander implements CreateSspInquiryFormExpanderInterface
{
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
        if (!$this->requestStack->getCurrentRequest()) {
            return false;
        }

        return $this->requestStack->getCurrentRequest()->query->count() === 0;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void
    {
        $this->addTypeField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addTypeField(FormBuilderInterface $builder, array $options)
    {
        $selectableTypes = $options[SspInquiryForm::OPTION_SSP_INQUIRY_TYPE_CHOICES][SspInquiryManagementConfig::GENERAL_SSP_INQUIRY_SOURCE] ?? [];

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
