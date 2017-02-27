<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationNodeFormType extends AbstractType
{

    const FIELD_NODE_TYPE = 'node_type';
    const FIELD_NAVIGATION_NODE_LOCALIZED_ATTRIBUTES = 'navigation_node_localized_attributes';
    const FIELD_IS_ACTIVE = 'is_active';

    const OPTION_NODE_TYPE_OPTIONS = 'node_type_options';
    const OPTION_NODE_TYPE_OPTION_ATTRIBUTES = 'node_type_option_attributes';

    /**
     * @var \Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeLocalizedAttributesFormType
     */
    protected $navigationNodeLocalizedAttributesFormType;

    /**
     * @param \Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeLocalizedAttributesFormType $navigationNodeLocalizedAttributesFormType
     */
    public function __construct(NavigationNodeLocalizedAttributesFormType $navigationNodeLocalizedAttributesFormType)
    {
        $this->navigationNodeLocalizedAttributesFormType = $navigationNodeLocalizedAttributesFormType;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'navigation_node';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_NODE_TYPE_OPTIONS);
        $resolver->setRequired(static::OPTION_NODE_TYPE_OPTION_ATTRIBUTES);

        $resolver->setDefaults([
            'data_class' => NavigationNodeTransfer::class,
            'required' => false,
        ]);
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
            ->addNodeTypeField($builder, $options)
            ->addNavigationNodeLocalizedAttributesForms($builder)
            ->addIsActiveField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addNodeTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_NODE_TYPE, ChoiceType::class, [
                'label' => 'Type',
                'placeholder' => 'None',
                'choices' => $options[self::OPTION_NODE_TYPE_OPTIONS],
                'choices_as_values' => true,
                'choice_attr' => $options[self::OPTION_NODE_TYPE_OPTION_ATTRIBUTES],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNavigationNodeLocalizedAttributesForms(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_NAVIGATION_NODE_LOCALIZED_ATTRIBUTES, CollectionType::class, [
                'type' => $this->navigationNodeLocalizedAttributesFormType,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_IS_ACTIVE, CheckboxType::class, [
                'label' => 'Active',
            ]);

        return $this;
    }

}
