<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\Persistence\PriceProductScheduleGuiRepositoryInterface getRepository()
 */
class PriceProductScheduleImportFormType extends AbstractType
{
    public const FIELD_PRICE_PRODUCT_SCHEDULE_NAME = 'priceProductScheduleName';
    public const FIELD_PRICE_PRODUCT_SCHEDULE_NAME_MAX_LENGTH = 255;
    public const FIELD_FILE_UPLOAD = 'fileUpload';
    public const BLOCK_PREFIX = 'priceProductScheduleImport';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addPriceProductScheduleListNameField($builder)
            ->addUploadFileField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPriceProductScheduleListNameField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_PRICE_PRODUCT_SCHEDULE_NAME,
            TextType::class,
            [
                'label' => 'Schedule name',
                'constraints' => [
                    new Required(),
                    new NotBlank(),
                    new Length(['max' => static::FIELD_PRICE_PRODUCT_SCHEDULE_NAME_MAX_LENGTH]),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUploadFileField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FILE_UPLOAD, FileType::class, [
            'label' => 'Select your CSV file',
            'constraints' => [
                new Required(),
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
