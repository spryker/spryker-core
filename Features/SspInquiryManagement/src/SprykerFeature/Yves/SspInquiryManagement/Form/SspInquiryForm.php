<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Form;

use Generated\Shared\Transfer\FileUploadTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerFeature\Yves\SspInquiryManagement\Form\Validator\Constraints\Files;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * @method \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 * @method \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementFactory getFactory()
 */
class SspInquiryForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_SUBJECT = 'subject';

    /**
     * @var string
     */
    protected const FIELD_DESCRIPTION = 'description';

    /**
     * @var string
     */
    public const FIELD_FILES = 'files';

    /**
     * @var string
     */
    public const OPTION_SSP_INQUIRY_TYPE_CHOICES = 'ssp_inquiry_type_choices';

    /**
     * @var string
     */
    public const OPTION_ALLOWED_MIME_TYPES = 'option_allowed_mime_types';

    /**
     * @var string
     */
    public const OPTION_ALLOWED_EXTENSIONS = 'option_allowed_extensions';

    /**
     * @var string
     */
    protected const ERROR_MIME_TYPE_MESSAGE = 'ssp_inquiry.file.mime_type.error';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'sspInquiryForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(static::OPTION_SSP_INQUIRY_TYPE_CHOICES)
            ->setRequired(static::OPTION_ALLOWED_EXTENSIONS)
            ->setRequired(static::OPTION_ALLOWED_MIME_TYPES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addSubjectField($builder)
            ->addDescriptionField($builder)
            ->addFileField($builder, $options);

        foreach ($this->getFactory()->getSspInquiryFormExpanders() as $sspInquiryFormExpander) {
            if (!$sspInquiryFormExpander->isApplicable()) {
                continue;
            }
              $sspInquiryFormExpander->expand($builder, $options);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSubjectField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SUBJECT, TextType::class, [
            'required' => true,
            'label' => 'ssp_inquiry.subject.label',
            'constraints' => [
                new NotBlank(),
            ],
            'attr' => ['maxlength' => 255],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DESCRIPTION, TextareaType::class, [
            'required' => true,
            'label' => 'ssp_inquiry.description.label',
            'attr' => ['maxlength' => 1000],
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addFileField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FILES, FileType::class, [
            'constraints' => [
                new Count([
                    'max' => $this->getConfig()->getFileMaxCount(),
                    'maxMessage' => 'ssp_inquiry.error.file.count.invalid',
                ]),
                new Files($this->getFilesConstraintConfiguration($options)),
            ],
            'required' => false,
            'multiple' => true,
            'label' => 'ssp_inquiry.files.label',
            'attr' => [
                'accept' => implode(', ', $this->getConfig()->getAllowedFileMimeTypes()),
                'acceptExtensions' => implode(', ', $this->getConfig()->getAllowedFileExtensions()),
                'maxSize' => $this->convertToReadableSize($this->normalizeBinaryFormat($this->getConfig()->getFileMaxSize())),
                'maxTotalSize' => $this->convertToReadableSize($this->normalizeBinaryFormat($this->getConfig()->getFilesMaxSize())),
                'maxCount' => $this->getConfig()->getFileMaxCount(),
            ],
        ]);

        $builder->get(static::FIELD_FILES)
            ->addModelTransformer(new CallbackTransformer(
                fn ($data) => $data,
                fn (?array $uploadedFiles = null) => $this->mapUploadedFilesToTransfers($uploadedFiles),
            ));

        return $this;
    }

    /**
     * @param array<mixed> $options
     *
     * @return array<mixed>
     */
    protected function getFilesConstraintConfiguration(array $options): array
    {
        $filesConstraintConfiguration = [
            'totalMaxSize' => $this->getConfig()->getFilesMaxSize(),
            'maxSize' => $this->getConfig()->getFileMaxSize(),
        ];

        $filesConstraintConfiguration += [
            'extensions' => $options[static::OPTION_ALLOWED_EXTENSIONS],
        ];

        return $filesConstraintConfiguration + [
            'mimeTypes' => $options[static::OPTION_ALLOWED_MIME_TYPES],
            'mimeTypesMessage' => static::ERROR_MIME_TYPE_MESSAGE,
            'maxSizeMessage' => 'The file {{ name }} is too large. Allowed maximum size is {{ limit }} {{ suffix }}.',
        ];
    }

    /**
     * @param array<\Symfony\Component\HttpFoundation\File\UploadedFile>|null $uploadedFiles
     *
     * @return array<\Generated\Shared\Transfer\FileUploadTransfer>|null
     */
    protected function mapUploadedFilesToTransfers(?array $uploadedFiles = null): ?array
    {
        if ($uploadedFiles === null) {
            return $uploadedFiles;
        }

        $fileUploadTransfers = [];
        foreach ($uploadedFiles as $uploadedFile) {
            if (!$uploadedFile instanceof UploadedFile) {
                continue;
            }
            $fileUploadTransfers[] = $this->createFileUploadTransfer($uploadedFile);
        }

        return $fileUploadTransfers;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @return \Generated\Shared\Transfer\FileUploadTransfer
     */
    protected function createFileUploadTransfer(UploadedFile $uploadedFile): FileUploadTransfer
    {
        $fileUploadTransfer = new FileUploadTransfer();
        $fileUploadTransfer->setClientOriginalName($uploadedFile->getClientOriginalName());
        $fileUploadTransfer->setRealPath((string)$uploadedFile->getRealPath());
        $fileUploadTransfer->setMimeTypeName($uploadedFile->getMimeType());
        $fileUploadTransfer->setClientOriginalExtension($uploadedFile->getClientOriginalExtension());
        $fileUploadTransfer->setSize($uploadedFile->getSize());

        return $fileUploadTransfer;
    }

    /**
     * @param int $size
     *
     * @return string
     */
    protected function convertToReadableSize(int $size): string
    {
        if ($size >= 1000 * 1000 * 1000) {
            return round($size / (1000 * 1000 * 1000), 2) . ' GB';
        } elseif ($size >= 1000 * 1000) {
            return round($size / (1000 * 1000), 2) . ' MB';
        } elseif ($size >= 1000) {
            return round($size / 1000, 2) . ' kB';
        } else {
            return $size . ' B';
        }
    }

    /**
     * @param string|int $totalMaxSize
     *
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     *
     * @return int
     */
    protected function normalizeBinaryFormat(int|string $totalMaxSize): int
    {
        $factors = [
            'k' => 1000,
            'ki' => 1 << 10,
            'm' => 1000 * 1000,
            'mi' => 1 << 20,
            'g' => 1000 * 1000 * 1000,
            'gi' => 1 << 30,
        ];
        if (ctype_digit((string)$totalMaxSize)) {
            $totalMaxSize = (int)$totalMaxSize;
        } elseif (preg_match('/^(\d++)(' . implode('|', array_keys($factors)) . ')$/i', (string)$totalMaxSize, $matches)) {
            $totalMaxSize = (int)$matches[1] * $factors[strtolower($matches[2])];
        } else {
            throw new ConstraintDefinitionException(sprintf('"%s" is not a valid maximum size.', $totalMaxSize));
        }

        return $totalMaxSize;
    }
}
