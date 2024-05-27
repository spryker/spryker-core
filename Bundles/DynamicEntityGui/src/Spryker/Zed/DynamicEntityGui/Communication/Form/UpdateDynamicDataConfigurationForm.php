<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\DynamicEntityGui\Communication\DynamicEntityGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig getConfig()
 */
class UpdateDynamicDataConfigurationForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_TABLE_COLUMNS = 'option_table_columns';

    /**
     * @var string
     */
    public const IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    public const FIELD_DEFINITIONS = 'field_definitions';

    /**
     * @var string
     */
    public const FIELD_IS_DELETABLE = 'is_deletable';

    /**
     * @var string
     */
    protected const FIELD_TABLE_NAME = 'table_name';

    /**
     * @var string
     */
    protected const FIELD_TABLE_ALIAS = 'table_alias';

    /**
     * @var string
     */
    protected const FIELD_IS_ENABLED = 'is_active';

    /**
     * @var string
     */
    protected const FIELD_DYNAMIC_ENTITY_DEFINITION = 'dynamic_entity_definition';

    /**
     * @var string
     */
    protected const LABEL_TABLE_NAME = 'Table name';

    /**
     * @var string
     */
    protected const LABEL_TABLE_ALIAS = 'Resource name';

    /**
     * @var string
     */
    protected const LABEL_IS_ENABLED = 'Is enabled';

    /**
     * @var string
     */
    protected const LABEL_IS_DELETABLE = 'Is deletable';

    /**
     * @var string
     */
    protected const LABEL_IDENTIFIER = 'Identifier';

    /**
     * @var string
     */
    protected const VALIDATION_ERROR_MESSAGE = 'Resource name is not valid. Allowed characters: a-z, A-Z, 0-9, _ and - ';

    /**
     * @var string
     */
    protected const VALIDATION_REGEX = '/^[a-zA-Z0-9_-]+$/';

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

        $resolver->setDefaults([static::OPTION_TABLE_COLUMNS => []]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addTableNameField($builder)
            ->addTableAliasField($builder)
            ->addIsEnabledField($builder)
            ->addIsDeletableField($builder)
            ->addIdentifierField($builder, $options)
            ->addColumnDefinitionSubForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTableNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TABLE_NAME, TextType::class, [
            'label' => static::LABEL_TABLE_NAME,
            'disabled' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTableAliasField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TABLE_ALIAS, TextType::class, [
            'label' => static::LABEL_TABLE_ALIAS,
            'constraints' => [
                $this->createTableAliasRegexConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsEnabledField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_ENABLED, CheckboxType::class, [
            'label' => static::LABEL_IS_ENABLED,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsDeletableField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_DELETABLE, CheckboxType::class, [
            'label' => static::LABEL_IS_DELETABLE,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addIdentifierField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::IDENTIFIER, ChoiceType::class, [
            'label' => static::LABEL_IDENTIFIER,
            'choices' => $options[static::OPTION_TABLE_COLUMNS],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addColumnDefinitionSubForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_DEFINITIONS, CollectionType::class, [
            'entry_type' => ColumnDefinitionsSubForm::class,
            'error_bubbling' => false,
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createTableAliasRegexConstraint(): Constraint
    {
        return new Regex([
            'pattern' => static::VALIDATION_REGEX,
            'message' => static::VALIDATION_ERROR_MESSAGE,
        ]);
    }
}
