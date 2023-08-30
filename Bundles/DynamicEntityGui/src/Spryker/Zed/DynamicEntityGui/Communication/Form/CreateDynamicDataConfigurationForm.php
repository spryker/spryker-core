<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\DynamicEntityGui\Communication\DynamicEntityGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig getConfig()
 */
class CreateDynamicDataConfigurationForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_TABLE_NAME_CHOICES = 'table_name_choices';

    /**
     * @var string
     */
    protected const FIELD_TABLE_NAME = 'table_name';

    /**
     * @var string
     */
    protected const LABEL_TABLE_NAME = 'Table Name';

    /**
     * @var string
     */
    protected const CHOICE_TABLE_NAME = 'tableName';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'dynamic-entity';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_TABLE_NAME_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addTableNameField($builder, $options[static::OPTION_TABLE_NAME_CHOICES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<\Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $choices
     *
     * @return $this
     */
    protected function addTableNameField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(
            static::FIELD_TABLE_NAME,
            Select2ComboBoxType::class,
            [
                'label' => static::LABEL_TABLE_NAME,
                'choices' => $choices,
                'choice_label' => static::CHOICE_TABLE_NAME,
                'choice_value' => static::CHOICE_TABLE_NAME,
                'required' => true,
            ],
        );

        return $this;
    }
}
