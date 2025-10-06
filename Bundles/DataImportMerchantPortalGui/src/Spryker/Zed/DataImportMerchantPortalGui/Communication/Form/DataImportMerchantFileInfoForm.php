<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Form;

use Spryker\Shared\Validator\Constraints\File;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\Constraint\CsvHeaderConstraint;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\DataImportMerchantPortalGui\DataImportMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\DataImportMerchantPortalGui\Communication\DataImportMerchantPortalGuiCommunicationFactory getFactory()
 */
class DataImportMerchantFileInfoForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_FILE = 'file';

    /**
     * @var string
     */
    public const FIELD_FORCE_PROCEED = 'forceProceed';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            DataImportMerchantFileForm::OPTION_POSSIBLE_CSV_HEADERS,
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
        $this->addFileField($builder, $options)
            ->addForceProceedField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addFileField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FILE, FileType::class, [
            'label' => false,
            'required' => false,
            'mapped' => true,
            'constraints' => $this->getFileFieldConstraints($options),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addForceProceedField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FORCE_PROCEED, CheckboxType::class, [
            'label' => 'Ignore warnings related to the uploaded file and proceed with import.',
            'mapped' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getFileFieldConstraints(array $options): array
    {
        return [
            new Required(),
            new NotBlank(),
            new File($this->getFileConstraintConfiguration()),
            new CsvHeaderConstraint([
                'headers' => $options[DataImportMerchantFileForm::OPTION_POSSIBLE_CSV_HEADERS],
            ]),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getFileConstraintConfiguration(): array
    {
        return [
            'extensions' => $this->getConfig()->getSupportedFileExtensions(),
            'maxSize' => $this->getConfig()->getMaxFileSizeInBytes(),
        ];
    }
}
