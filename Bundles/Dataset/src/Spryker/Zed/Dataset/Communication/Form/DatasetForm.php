<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Form;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\Dataset\Business\DatasetFacadeInterface getFacade()
 * @method \Spryker\Zed\Dataset\Communication\DatasetCommunicationFactory getFactory()
 * @method \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface getQueryContainer()
 */
class DatasetForm extends AbstractType
{
    const FIELD_DATASET_NAME = 'name';
    const FIELD_ID_DATASET = 'idDataset';
    const DATASET_DATA_CONTENT = 'spyDatasetRowColumnValues';
    const DATASET_FILE_CONTENT = 'contentFile';
    const FIELD_USE_REAL_NAME = 'useRealName';
    const DATASET_LOCALIZED_ATTRIBUTES = 'getSpyDatasetLocalizedAttributess';
    const OPTION_DATA_CLASS = 'data_class';
    const OPTION_AVAILABLE_LOCALES = 'option_available_locales';
    const DATASET_HAS_DATA = 'datasetHasData';
    const GROUP_UNIQUE_DATASET_NAME_CHECK = 'unique_dataset_name_check';

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
            ->addDatasetContentField($builder, $options)
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
        $resolver->setRequired(static::DATASET_HAS_DATA);
        $resolver->setDefaults([
            static::OPTION_DATA_CLASS => SpyDatasetEntityTransfer::class,
        ]);
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $defaultData = (array)$form->getConfig()->getData();
                $submittedData = $form->getData();

                if (array_key_exists(self::FIELD_DATASET_NAME, $defaultData) === false ||
                    $defaultData[self::FIELD_DATASET_NAME] !== $submittedData[self::FIELD_DATASET_NAME]
                ) {
                    return [Constraint::DEFAULT_GROUP, self::GROUP_UNIQUE_DATASET_NAME_CHECK];
                }

                return [Constraint::DEFAULT_GROUP];
            },
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
        $builder->add(self::FIELD_DATASET_NAME, TextType::class, [
            'label' => 'Name',
            'constraints' => $this->createDatasetConstraints(),
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function createDatasetConstraints()
    {
        $constraints = $this->getFieldDefaultConstraints();

        $constraints[] = new Callback([
            'callback' => function ($name, ExecutionContextInterface $contextInterface) {
                if ($this->getFacade()->hasDatasetName($name)) {
                    $contextInterface->addViolation('The name already exists.');
                }
            },
            'groups' => [self::GROUP_UNIQUE_DATASET_NAME_CHECK],
        ]);

        return $constraints;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getFieldDefaultConstraints()
    {
        return [
            new NotBlank(),
            new Required(),
        ];
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
            'entry_type' => $this->getFactory()->createDatasetLocalizedAttributesForm(),
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
     * @param array|null $options
     *
     * @return $this
     */
    protected function addDatasetContentField(FormBuilderInterface $builder, array $options = null)
    {
        $builder->add(static::DATASET_FILE_CONTENT, FileType::class, [
            'required' => empty($options[static::DATASET_HAS_DATA]),
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
