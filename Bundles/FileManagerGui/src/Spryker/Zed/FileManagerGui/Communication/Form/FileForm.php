<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form;

use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Spryker\Zed\FileManagerGui\Communication\Form\Validator\Constraints\File;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\FileManagerGui\FileManagerGuiConfig getConfig()
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class FileForm extends AbstractType
{
    public const FIELD_FILE_NAME = 'fileName';
    public const FILED_FILE_UPLOAD = 'fileUpload';
    public const FIELD_ID_FILE = 'idFile';
    public const FIELD_USE_REAL_NAME = 'useRealName';
    public const FILE_LOCALIZED_ATTRIBUTES = 'localizedAttributes';

    public const OPTION_DATA_CLASS = 'data_class';
    public const OPTION_AVAILABLE_LOCALES = 'option_available_locales';
    public const OPTION_ALLOWED_MIME_TYPES = 'option_allowed_mime_types';

    protected const ERROR_MIME_TYPE_MESSAGE = 'File type is not allowed for uploading';
    protected const ERROR_FILE_MISSED_EDIT_MESSAGE = 'Upload a file or specify a new file name';
    protected const ERROR_FILE_MISSED_ADD_MESSAGE = 'Upload a file';
    protected const ERROR_FILE_NAME_MISSED_ADD_MESSAGE = 'Specify a file name or use real one';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addFileNameField($builder)
            ->addIdFileField($builder)
            ->addUseRealNameOption($builder)
            ->addFileLocalizedAttributesForm($builder, $options)
            ->addUploadedFileField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_AVAILABLE_LOCALES);
        $resolver->setRequired(static::OPTION_ALLOWED_MIME_TYPES);

        $resolver->setDefaults([
            static::OPTION_DATA_CLASS => FileTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFileNameField(FormBuilderInterface $builder)
    {
        $formData = $builder->getData();
        $fileNameCallback = function ($object, ExecutionContextInterface $context) use ($formData) {
            if (!empty($formData[static::FIELD_ID_FILE])) {
                if (empty($formData[static::FILED_FILE_UPLOAD]) && empty($formData[static::FIELD_FILE_NAME])) {
                    $context->addViolation(static::ERROR_FILE_MISSED_EDIT_MESSAGE);
                }
            } else {
                if (empty($formData[static::FILED_FILE_UPLOAD])) {
                    $context->addViolation(static::ERROR_FILE_MISSED_ADD_MESSAGE);
                } elseif (empty($formData[static::FIELD_FILE_NAME]) && empty($formData[static::FIELD_USE_REAL_NAME])) {
                    $context->addViolation(static::ERROR_FILE_NAME_MISSED_ADD_MESSAGE);
                }
            }
        };

        $builder->add(static::FIELD_FILE_NAME, TextType::class, [
            'required' => !empty($formData[static::FIELD_USE_REAL_NAME]),
            'constraints' => [
                new Callback($fileNameCallback),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdFileField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_FILE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addUploadedFileField(FormBuilderInterface $builder, array $options)
    {
        $formData = $builder->getData();
        $builder->add(static::FILED_FILE_UPLOAD, FileType::class, [
            'required' => empty($formData[static::FIELD_ID_FILE]),
            'constraints' => [
                new File([
                    'maxSize' => $this->getConfig()->getDefaultFileMaxSize(),
                    'mimeTypes' => $options[static::OPTION_ALLOWED_MIME_TYPES],
                    'mimeTypesMessage' => static::ERROR_MIME_TYPE_MESSAGE,
                ]),
            ],
        ]);

        $builder->get(static::FILED_FILE_UPLOAD)
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($data) {
                        return $data;
                    },
                    function (?UploadedFile $uploadedFile = null) {
                        return $this->mapUploadedFileToTransfer($uploadedFile);
                    }
                )
            );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUseRealNameOption(FormBuilderInterface $builder)
    {
        $formData = $builder->getData();

        if (empty($formData[static::FIELD_ID_FILE])) {
            $builder->add(static::FIELD_USE_REAL_NAME, CheckboxType::class, [
                'attr' => [
                    'checked' => 'checked',
                ],
                'required' => false,
                'label' => 'Use file name',
            ]);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array|null $options
     *
     * @return $this
     */
    protected function addFileLocalizedAttributesForm(FormBuilderInterface $builder, ?array $options = null)
    {
        $builder->add(static::FILE_LOCALIZED_ATTRIBUTES, CollectionType::class, [
            'entry_type' => FileLocalizedAttributesForm::class,
            'allow_add' => true,
            'allow_delete' => true,

            'entry_options' => [
                static::OPTION_AVAILABLE_LOCALES => $options[static::OPTION_AVAILABLE_LOCALES],
            ],
        ]);

        return $this;
    }

    /**
     * @param null|\Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @return null|\Generated\Shared\Transfer\FileUploadTransfer
     */
    protected function mapUploadedFileToTransfer(?UploadedFile $uploadedFile = null)
    {
        if ($uploadedFile === null) {
            return $uploadedFile;
        }

        $fileUploadTransfer = new FileUploadTransfer();
        $fileUploadTransfer->setClientOriginalName($uploadedFile->getClientOriginalName());
        $fileUploadTransfer->setRealPath($uploadedFile->getRealPath());
        $fileUploadTransfer->setMimeTypeName($uploadedFile->getMimeType());
        $fileUploadTransfer->setClientOriginalExtension($uploadedFile->getClientOriginalExtension());
        $fileUploadTransfer->setSize($uploadedFile->getSize());

        return $fileUploadTransfer;
    }
}
