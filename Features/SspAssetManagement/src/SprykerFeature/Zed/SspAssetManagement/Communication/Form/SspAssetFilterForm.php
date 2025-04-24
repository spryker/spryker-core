<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Communication\Form;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Zed\SspAssetManagement\Communication\SspAssetManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface getRepository()
 */
class SspAssetFilterForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_STATUSES = 'statuses';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(static::OPTION_STATUSES)
            ->setDefaults([
                'method' => 'GET',
                'data_class' => SspAssetConditionsTransfer::class,
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
        $this->addStatusField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addStatusField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(SspAssetConditionsTransfer::STATUS, ChoiceType::class, [
            'label' => 'Status',
            'choices' => $options[static::OPTION_STATUSES],
            'required' => false,
            'placeholder' => 'Select Status',
        ]);

        return $this;
    }
}
