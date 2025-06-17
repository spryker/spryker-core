<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Form;

use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspAssetForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var string
     */
    protected const FIELD_SERIAL_NUMBER = 'serialNumber';

    /**
     * @var string
     */
    protected const FIELD_NOTE = 'note';

    /**
     * @var string
     */
    public const FIELD_IMAGE = 'asset_image';

    /**
     * @var string
     */
    public const OPTION_ORIGINAL_IMAGE_URL = 'imageUrl';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'assetForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SspAssetTransfer::class,
            static::OPTION_ORIGINAL_IMAGE_URL => null,
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
        $this
            ->addNameField($builder)
            ->addSerialNumberField($builder)
            ->addNoteField($builder)
            ->addImageField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'self_service_portal.asset.form.name',
            'required' => true,
            'sanitize_xss' => true,
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => 3,
                    'max' => 255,
                    'minMessage' => 'self_service_portal.asset.form.name.validation.min',
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSerialNumberField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SERIAL_NUMBER, TextType::class, [
            'label' => 'self_service_portal.asset.form.serial_number',
            'required' => false,
            'sanitize_xss' => true,
            'constraints' => [
                new Length([
                    'max' => 255,
                    'minMessage' => 'self_service_portal.asset.form.serial_number.validation.min',
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNoteField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NOTE, TextareaType::class, [
            'label' => 'self_service_portal.asset.form.note',
            'required' => false,
            'sanitize_xss' => true,
            'constraints' => [
                new Length([
                    'max' => 1000,
                    'minMessage' => 'self_service_portal.asset.form.note.validation.min',
                ]),
            ],
            'attr' => ['rows' => 5],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addImageField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_IMAGE, SspAssetImageForm::class, [
            'mapped' => false,
            SspAssetImageForm::OPTION_ORIGINAL_IMAGE_URL => $options[static::OPTION_ORIGINAL_IMAGE_URL] ?? null,
        ]);

        return $this;
    }
}
