<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryGui\CategoryGuiConfig getConfig()
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 */
class CategoryType extends CommonCategoryType
{
    public const OPTION_PARENT_CATEGORY_NODE_CHOICES = 'parent_category_node_choices';

    public const FIELD_PARENT_CATEGORY_NODE = 'parent_category_node';
    public const FIELD_EXTRA_PARENTS = 'extra_parents';

    protected const LABEL_PARENT_CATEGORY_NODE = 'Parent';
    protected const LABEL_EXTRA_PARENTS = 'Additional Parents';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(static::OPTION_PARENT_CATEGORY_NODE_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this
            ->addParentNodeField($builder, $options[static::OPTION_PARENT_CATEGORY_NODE_CHOICES])
            ->addExtraParentsField($builder, $options[static::OPTION_PARENT_CATEGORY_NODE_CHOICES])
            ->addStoreRelationEventSubscriber($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addParentNodeField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_PARENT_CATEGORY_NODE, Select2ComboBoxType::class, [
            'property_path' => 'parentCategoryNode',
            'label' => static::LABEL_PARENT_CATEGORY_NODE,
            'choices' => $choices,
            'choice_label' => 'name',
            'choice_value' => 'idCategoryNode',
            'group_by' => 'path',
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addExtraParentsField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_EXTRA_PARENTS, Select2ComboBoxType::class, [
            'label' => static::LABEL_EXTRA_PARENTS,
            'choices' => $choices,
            'choice_label' => 'name',
            'choice_value' => 'idCategoryNode',
            'multiple' => true,
            'group_by' => 'path',
            'required' => false,
        ]);

        $builder->get(static::FIELD_EXTRA_PARENTS)->addModelTransformer(new CallbackTransformer(
            function ($extraParents) {
                return (array)$extraParents;
            },
            function ($extraParents) {
                return new ArrayObject($extraParents);
            }
        ));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStoreRelationEventSubscriber(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $idCategoryNode = $this->extractIdParentCategoryNodeFromEvent($event);
            if ($idCategoryNode === null) {
                return $this;
            }

            $form = $event->getForm();

            $options = $form->get(static::FIELD_STORE_RELATION)->getConfig()->getOptions();
            $options[static::OPTION_INACTIVE_CHOICES] = $this->getFactory()
                ->createInactiveCategoryStoresFinder()
                ->findStoresByIdCategoryNode($idCategoryNode);

            $form->add(
                static::FIELD_STORE_RELATION,
                $this->getFactory()->getStoreRelationFormTypePlugin()->getType(),
                $options
            );
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return int|null
     */
    protected function extractIdParentCategoryNodeFromEvent(FormEvent $event): ?int
    {
        $categoryTransfer = $event->getData();
        if (!($categoryTransfer instanceof CategoryTransfer)) {
            return null;
        }

        $categoryNodeParentTransfer = $categoryTransfer->getParentCategoryNode();
        if ($categoryNodeParentTransfer !== null) {
            return $categoryNodeParentTransfer->getIdCategoryNode();
        }

        return null;
    }
}
