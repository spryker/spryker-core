<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\ContentProductGui\Communication\ContentProductGuiCommunicationFactory getFactory()
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
                /** @var \Generated\Shared\Transfer\ContentProductAbstractListTransfer $contentProductAbstractList */
                $contentProductAbstractList = $form->getNormData();

                foreach ($contentProductAbstractList->getIdProductAbstracts() as $idProductAbstract) {
                    if ($idProductAbstract) {
                        return [Constraint::DEFAULT_GROUP];
                    }
                }

                return [];
            },
            'attr' => [
                'template_path' => static::TEMPLATE_PATH,
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $productAbstractViewTable = $this->getFactory()->createProductAbstractViewTable(
            $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
            $view->vars['value']->getIdProductAbstracts(),
            $view->parent->vars['name']
        );
        $productAbstractSelectedTable = $this->getFactory()->createProductAbstractSelectedTable(
            $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
            $view->vars['value']->getIdProductAbstracts(),
            $view->parent->vars['name']
        );
        $data = $productAbstractSelectedTable->getData();
        $view->vars['tables']['productAbstractViewTable'] = $productAbstractViewTable->render();
        $view->vars['tables']['productAbstractSelectedTable'] = $productAbstractSelectedTable->render();
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
            'entry_type' => HiddenType::class,
            'label' => false,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'label' => false,
                'attr' => [
                    'placeholder' => static::PLACEHOLDER_ID_ABSTRACT_PRODUCTS,
                ],
                'constraints' => $this->getTextFieldConstraints(),
            ],
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function getTextFieldConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 100]),
        ];
    }
}
