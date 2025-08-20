<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form;

use Generated\Shared\Transfer\SspModelTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class SspModelForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var string
     */
    protected const FIELD_CODE = 'code';

    /**
     * @var string
     */
    protected const FIELD_REFERENCE = 'reference';

    /**
     * @var string
     */
    public const FIELD_IMAGE = 'model_image';

    /**
     * @var string
     */
    public const FIELD_IMAGE_URL = 'image_url';

    /**
     * @var string
     */
    public const OPTION_ORIGINAL_IMAGE_URL = 'imageUrl';

    /**
     * @var string
     */
    public const FORM_NAME = 'modelForm';

    /**
     * @uses SspModelValidator::ERROR_MESSAGE_NAME_EMPTY
     *
     * @var string
     */
    protected const MESSAGE_NAME_REQUIRED = 'Model name is required.';

    /**
     * @uses SspModelValidator::ERROR_MESSAGE_NAME_TOO_LONG
     *
     * @var string
     */
    protected const MESSAGE_NAME_TOO_LONG = 'Model name cannot be longer than {{ limit }} characters.';

    /**
     * @uses SspModelValidator::ERROR_MESSAGE_CODE_TOO_LONG
     *
     * @var string
     */
    protected const MESSAGE_CODE_TOO_LONG = 'Model code cannot be longer than {{ limit }} characters.';

    /**
     * @uses SspModelValidator::ERROR_MESSAGE_IMAGE_URL_INVALID
     *
     * @var string
     */
    protected const MESSAGE_IMAGE_URL_INVALID = 'Please enter a valid image URL.';

    /**
     * @var string
     */
    protected const LABEL_MODEL_NAME = 'Model Name';

    /**
     * @var string
     */
    protected const LABEL_MODEL_CODE = 'Model Code';

    /**
     * @var string
     */
    protected const LABEL_IMAGE_URL = 'Image URL';

    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SspModelTransfer::class,
            static::OPTION_ORIGINAL_IMAGE_URL => null,
        ]);

        $resolver->setDefined([
            static::OPTION_ORIGINAL_IMAGE_URL,
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
            ->addCodeField($builder)
            ->addImageUrlField($builder)
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
            'label' => static::LABEL_MODEL_NAME,
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => static::MESSAGE_NAME_REQUIRED,
                ]),
                new Length([
                    'max' => $this->getConfig()->getSspModelNameMaxLength(),
                    'maxMessage' => static::MESSAGE_NAME_TOO_LONG,
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
    protected function addCodeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CODE, TextType::class, [
            'label' => static::LABEL_MODEL_CODE,
            'required' => false,
            'constraints' => [
                new Length([
                    'max' => $this->getConfig()->getSspModelCodeMaxLength(),
                    'maxMessage' => static::MESSAGE_CODE_TOO_LONG,
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
    protected function addImageUrlField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IMAGE_URL, TextType::class, [
            'label' => static::LABEL_IMAGE_URL,
            'required' => false,
            'constraints' => [
                new Url([
                    'message' => static::MESSAGE_IMAGE_URL_INVALID,
                ]),
            ],
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
        $builder->add(static::FIELD_IMAGE, SspModelImageForm::class, [
            'mapped' => false,
            SspModelImageForm::OPTION_ORIGINAL_IMAGE_URL => $options[static::OPTION_ORIGINAL_IMAGE_URL] ?? null,
        ]);

        return $this;
    }
}
