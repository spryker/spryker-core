<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Form;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use ArrayObject;

class DatasetForm extends AbstractType
{
    const FIELD_DATASET_NAME = 'name';
    const FIELD_ID_DATASET = 'idDataset';
    const DATASET_DATA_CONTENT = 'spyDatasetRowColValues';
    const DATASET_FILE_CONTENT = 'contentFile';
    const FIELD_USE_REAL_NAME = 'useRealName';
    const DATASET_LOCALIZED_ATTRIBUTES = 'getSpyDatasetLocalizedAttributess';
    const OPTION_DATA_CLASS = 'data_class';
    const OPTION_AVAILABLE_LOCALES = 'option_available_locales';

    /**
     * @var \Spryker\Zed\Dataset\Communication\Form\DatasetLocalizedAttributesForm
     */
    protected $datasetLocalizedAttributesForm;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $datasetLocalizedAttributesForm
     */
    public function __construct(FormTypeInterface $datasetLocalizedAttributesForm)
    {
        $this->datasetLocalizedAttributesForm = $datasetLocalizedAttributesForm;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dataset';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdDatasetField($builder)
            ->addDatasetContentField($builder)
            ->addDatasetNameField($builder)
            ->addDatasetLocalizedAttributesForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_AVAILABLE_LOCALES);
        $resolver->setDefaults([
            static::OPTION_DATA_CLASS => SpyDatasetEntityTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDatasetNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DATASET_NAME, TextType::class, [
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdDatasetField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_DATASET, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array|null $options
     *
     * @return $this
     */
    protected function addDatasetLocalizedAttributesForm(FormBuilderInterface $builder, array $options = null)
    {
        $builder->add(static::DATASET_LOCALIZED_ATTRIBUTES, CollectionType::class, [
            'entry_type' => $this->datasetLocalizedAttributesForm,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                static::OPTION_AVAILABLE_LOCALES => $options[static::OPTION_AVAILABLE_LOCALES],
            ],
        ]);
        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDatasetContentField(FormBuilderInterface $builder)
    {
        $builder->add(static::DATASET_FILE_CONTENT, FileType::class, [
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1M',
                    'mimeTypes' => [
                        'text/csv',
                        'text/x-csv',
                        'text/plain',
                    ],
                    'mimeTypesMessage' => 'Please upload a CSV',
                ]),
            ],
        ]);

        return $this;
    }
}
