<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\Expander;

use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\SspInquiryForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateSspAssetSspInquiryFormExpander implements CreateSspInquiryFormExpanderInterface
{
    /**
     * @var string
     */
    protected const FIELD_SSP_ASSET_REFERENCE = 'sspAssetReference';

    /**
     * @var string
     */
    protected const QUERY_PARAM_SSP_ASSET_REFERENCE = 'sspAssetReference';

    /**
     * @var string
     */
    protected const FIELD_TYPE = 'type';

    /**
     * @var int
     */
    protected const SINGLE_OPTION_COUNT = 1;

    public function __construct(protected RequestStack $requestStack)
    {
    }

    public function isApplicable(): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            return false;
        }

        return $request->query->has(static::QUERY_PARAM_SSP_ASSET_REFERENCE) && !empty($request->query->get(static::QUERY_PARAM_SSP_ASSET_REFERENCE));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void
    {
        $this->addSspAssetReference($builder);
        $this->addTypeField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSspAssetReference(FormBuilderInterface $builder)
    {
        if (!$this->requestStack->getCurrentRequest()) {
            return $this;
        }

        $builder->add(static::FIELD_SSP_ASSET_REFERENCE . '_display', TextType::class, [
            'priority' => 2,
            'label' => 'self_service_portal.inquiry.ssp_asset_reference.label',
            'constraints' => [
                new NotBlank(),
            ],
            'data' => $this->requestStack->getCurrentRequest()->query->get(static::QUERY_PARAM_SSP_ASSET_REFERENCE),
            'disabled' => true,
        ]);

        $builder->add(static::FIELD_SSP_ASSET_REFERENCE, HiddenType::class, [
            'data' => $this->requestStack->getCurrentRequest()->query->get(static::QUERY_PARAM_SSP_ASSET_REFERENCE),
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
        $selectableTypes = $options[SspInquiryForm::OPTION_SSP_INQUIRY_TYPE_CHOICES][SelfServicePortalConfig::SSP_ASSET_SSP_INQUIRY_SOURCE] ?? [];

        $mappedTypes = array_combine(array_map(fn ($type) => 'self_service_portal.inquiry.type.' . $type, $selectableTypes), $selectableTypes);

        if (count($selectableTypes) === static::SINGLE_OPTION_COUNT) {
            $builder->add(static::FIELD_TYPE, HiddenType::class, [
                'data' => $selectableTypes[0],
            ]);
        }

        $builder->add(static::FIELD_TYPE . (count($selectableTypes) === static::SINGLE_OPTION_COUNT ? '_display' : ''), ChoiceType::class, [
            'priority' => 1,
            'choices' => $mappedTypes,
            'required' => true,
            'label' => 'self_service_portal.inquiry.type.label',
            'placeholder' => 'self_service_portal.inquiry.create.select_type',
            'constraints' => [
                new NotBlank(),
            ],
            'attr' => [
                'required' => 'required',
            ],
            'disabled' => count($selectableTypes) === static::SINGLE_OPTION_COUNT,
            'data' => count($selectableTypes) === static::SINGLE_OPTION_COUNT ? $selectableTypes[0] : null,
        ]);

        return $this;
    }
}
