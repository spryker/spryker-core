<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspAssetBusinessUnitRelationsForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_BUSINESS_UNIT_ID = 'id_business_unit';

    /**
     * @var string
     */
    public const FIELD_ASSET_REFERENCE = 'ssp_asset_reference';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addBusinessUnitIdField($builder)
            ->addAssetReferenceField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addBusinessUnitIdField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BUSINESS_UNIT_ID,
            HiddenType::class,
            [
                'required' => true,
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAssetReferenceField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ASSET_REFERENCE,
            HiddenType::class,
            [
                'required' => true,
            ],
        );

        return $this;
    }
}
