<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * @method \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantCommissionGui\Communication\MerchantCommissionGuiCommunicationFactory getFactory()
 */
class MerchantCommissionImportForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_FILE_UPLOAD = 'file_upload';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => false,
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
        $this->addUploadFileField($builder);
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
            'attr' => [
                'accept' => $this->getAcceptMimeTypes(),
            ],
            'constraints' => [
                new File([
                    'extensions' => $this->getConfig()->getFileAllowedExtensionsWithMimeTypes(),
                    'maxSize' => $this->getConfig()->getMaxFileSize(),
                ]),
            ],
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @return string
     */
    protected function getAcceptMimeTypes(): string
    {
        $acceptMimeTypes = [];
        foreach ($this->getConfig()->getFileAllowedExtensionsWithMimeTypes() as $mimeTypes) {
            $acceptMimeTypes = array_merge($acceptMimeTypes, $mimeTypes);
        }

        return implode(', ', $acceptMimeTypes);
    }
}
