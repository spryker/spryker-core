<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\SelectType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\DataImportMerchantPortalGui\DataImportMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\DataImportMerchantPortalGui\Communication\DataImportMerchantPortalGuiCommunicationFactory getFactory()
 */
class DataImportMerchantFileForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_FILE_INFO = 'fileInfo';

    /**
     * @var string
     */
    public const OPTION_TYPE_CHOICES = 'option_type_choices';

    /**
     * @var string
     */
    public const OPTION_POSSIBLE_CSV_HEADERS = 'option_possible_csv_headers';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_TYPE_CHOICES,
            static::OPTION_POSSIBLE_CSV_HEADERS,
        ]);

        $resolver->setDefaults([
            'data_class' => DataImportMerchantFileTransfer::class,
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
        parent::buildForm($builder, $options);

        $this
            ->addImporterTypeField($builder, $options)
            ->addFileInfo($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addImporterTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(DataImportMerchantFileTransfer::IMPORTER_TYPE, SelectType::class, [
            'label' => 'File Type',
            'placeholder' => 'Select type',
            'choices' => $options[static::OPTION_TYPE_CHOICES],
            'required' => true,
            'constraints' => $this->getImporterTypeFieldConstraints($options),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function addFileInfo(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_FILE_INFO, DataImportMerchantFileInfoForm::class, [
            'label' => false,
            'mapped' => false,
            static::OPTION_POSSIBLE_CSV_HEADERS => $options[static::OPTION_POSSIBLE_CSV_HEADERS],
        ]);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getImporterTypeFieldConstraints(array $options): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Choice(['choices' => $options[static::OPTION_TYPE_CHOICES]]),
        ];
    }
}
