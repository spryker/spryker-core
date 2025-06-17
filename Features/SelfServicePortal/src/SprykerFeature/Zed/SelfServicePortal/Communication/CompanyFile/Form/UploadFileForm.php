<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class UploadFileForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_FILE_UPLOAD = 'fileUploads';

    /**
     * @var string
     */
    public const OPTION_DATA_CLASS = 'data_class';

    /**
     * @var string
     */
    protected const ERROR_FILE_REQUIRED = 'Please select a file to upload';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addUploadedFileField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUploadedFileField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FILE_UPLOAD, FileType::class, [
            'label' => 'Files',
            'required' => true,
            'mapped' => false,
            'multiple' => true,
            'attr' => [
                'multiple' => true,
                'accept' => implode(',', $this->getConfig()->getAllowedFileExtensions()),
                'max' => 4,
                'size' => $this->getConfig()->getMaxFileSize(),
                'data-qa' => 'file-upload-button',
            ],
            'constraints' => [
                new All([
                    'constraints' => [
                        new NotBlank(['message' => static::ERROR_FILE_REQUIRED]),
                        new File([
                            'maxSize' => $this->getConfig()->getMaxFileSize(),
                            'mimeTypes' => $this->getConfig()->getAllowedMimeTypes(),
                            'mimeTypesMessage' => 'Please upload valid documents (PDF) or images (JPEG, PNG)',
                        ]),
                    ],
                ]),
            ],
        ]);

        return $this;
    }
}
