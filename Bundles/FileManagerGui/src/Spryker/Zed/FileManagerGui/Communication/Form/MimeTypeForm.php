<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form;

use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileManagerGui\FileManagerGuiConfig getConfig()
 */
class MimeTypeForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ID_MIME_TYPE = 'idMimeType';

    /**
     * @var string
     */
    public const FIELD_NAME = 'name';

    /**
     * @var string
     */
    public const FIELD_COMMENT = 'comment';

    /**
     * @var string
     */
    public const FIELD_IS_ALLOWED = 'isAllowed';

    /**
     * @var string
     */
    public const FIELD_EXTENSIONS = 'extensions';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MimeTypeTransfer::class,
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
        $this->addIdMimeTypeField($builder)
            ->addNameField($builder)
            ->addCommentField($builder)
            ->addExtensionsField($builder)
            ->addIsAllowedField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdMimeTypeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_MIME_TYPE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_NAME,
            TextType::class,
            [
                'label' => 'MIME type',
                'constraints' => [
                    new NotBlank(),
                ],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCommentField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_COMMENT,
            TextareaType::class,
            [
                'required' => false,
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addExtensionsField(FormBuilderInterface $builder)
    {
        $fieldOptions = [
            'required' => false,
        ];

        if ($this->getConfig()->isFileExtensionValidationEnabled()) {
            $fieldOptions = [
                'constraints' => new NotBlank(),
                'required' => true,
            ];
        }

        $builder->add(static::FIELD_EXTENSIONS, TextType::class, $fieldOptions);

        $builder->get(static::FIELD_EXTENSIONS)
            ->addModelTransformer(new CallbackTransformer(
                function ($extensions): string {
                    return implode(', ', $extensions);
                },
                function ($extensions): array {
                    return array_unique(array_filter(array_map('trim', explode(',', $extensions))));
                },
            ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsAllowedField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_IS_ALLOWED,
            CheckboxType::class,
            [
                'required' => false,
            ],
        );

        return $this;
    }
}
