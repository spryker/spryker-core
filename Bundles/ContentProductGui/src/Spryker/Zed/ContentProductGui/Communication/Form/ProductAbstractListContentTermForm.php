<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ContentProductGui\Communication\ContentProductGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentProductGui\ContentProductGuiConfig getConfig()
 */
class ProductAbstractListContentTermForm extends AbstractType
{
    public const FIELD_ID_ABSTRACT_PRODUCTS = 'idProductAbstracts';
    public const PLACEHOLDER_ID_ABSTRACT_PRODUCTS = 'id';

    protected const TEMPLATE_PATH = '@ContentProductGui/ProductAbstractList/product_abstract_list.twig';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                /** @var \Generated\Shared\Transfer\LocalizedContentTransfer $localizedContentTransfer */
                $localizedContentTransfer = $form->getParent()->getData();
                if ($localizedContentTransfer->getFkLocale() === null) {
                    return [Constraint::DEFAULT_GROUP];
                }
                /** @var \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer */
                $contentProductAbstractListTermTransfer = $form->getNormData();

                foreach ($contentProductAbstractListTermTransfer->getIdProductAbstracts() as $idProductAbstract) {
                    if ($idProductAbstract) {
                        return [Constraint::DEFAULT_GROUP];
                    }
                }

                return [];
            },
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['tables']['productAbstractViewTable'] = $this->getFactory()->createProductAbstractViewTable(
            $view->parent->vars['name']
        );
        $view->vars['tables']['productAbstractSelectedTable'] = $this->getFactory()->createProductAbstractSelectedTable(
            $view->vars['value']->getIdProductAbstracts(),
            $view->parent->vars['name']
        );
        $view->vars['attr']['template_path'] = static::TEMPLATE_PATH;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'abstract-product-list';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdProductAbstractsField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductAbstractsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_ABSTRACT_PRODUCTS, CollectionType::class, [
            'entry_type' => IntegerType::class,
            'label' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'label' => false,
                'grouping' => true,
            ],
            'constraints' => [
                $this->getFactory()->createContentProductAbstractListConstraint(),

            ],
        ])->get(static::FIELD_ID_ABSTRACT_PRODUCTS)->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event): void {
                if (!$event->getData()) {
                    return;
                }
                // Symfony Forms requires removing empty values from CollectionType to get correct items order
                $ids = array_filter(array_values($event->getData()));
                $event->setData($ids);
                $event->getForm()->setData($ids);
            }
        );

        return $this;
    }
}
