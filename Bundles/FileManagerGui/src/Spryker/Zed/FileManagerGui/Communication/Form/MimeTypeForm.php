<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form;

use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class MimeTypeForm extends AbstractType
{
    public const FIELD_ID_MIME_TYPE = 'idMimeType';
    public const FIELD_NAME = 'name';
    public const FIELD_COMMENT = 'comment';
    public const FIELD_IS_ALLOWED = 'isAllowed';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MimeTypeTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_ID_MIME_TYPE,
            HiddenType::class
        );

        $builder->add(
            static::FIELD_NAME,
            TextType::class,
            [
                'label' => 'MIME type',
                'constraints' => [
                    new NotBlank(),
                ],
            ]
        );

        $builder->add(
            static::FIELD_COMMENT,
            TextareaType::class,
            [
                'required' => false,
            ]
        );

        $builder->add(
            static::FIELD_IS_ALLOWED,
            CheckboxType::class,
            [
                'required' => false,
            ]
        );
    }
}
