<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form;

use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspInquiryFilterForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_TYPES = 'types';

    /**
     * @var string
     */
    public const OPTION_STATUSES = 'statuses';

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_TYPES)
            ->setRequired(static::OPTION_STATUSES)
            ->setDefaults([
                'method' => 'GET',
                'data_class' => SspInquiryConditionsTransfer::class,
            ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addTypeField($builder, $options)
            ->addStatusField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(SspInquiryConditionsTransfer::TYPE, ChoiceType::class, [
            'label' => 'Type',
            'choices' => $options[static::OPTION_TYPES],
            'required' => false,
            'placeholder' => 'Select Type',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addStatusField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(SspInquiryConditionsTransfer::STATUS, ChoiceType::class, [
            'label' => 'Status',
            'choices' => $options[static::OPTION_STATUSES],
            'required' => false,
            'placeholder' => 'Select Status',
        ]);

        return $this;
    }
}
