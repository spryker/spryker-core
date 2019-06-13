<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ContentProductSetGui\Communication\ContentProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentProductSetGui\ContentProductSetGuiConfig getConfig()
 */
class ProductSetContentTermForm extends AbstractType
{
    public const FIELD_ID_PRODUCT_SET = 'idProductSet';

    protected const TEMPLATE_PATH = '@ContentProductSetGui/ProductSet/product_set.twig';

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
                /** @var \Generated\Shared\Transfer\ContentProductSetTermTransfer $contentProductSet */
                $contentProductSet = $form->getNormData();
                if ($contentProductSet->getIdProductSet()) {
                    return [Constraint::DEFAULT_GROUP];
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
        $view->vars['tables']['productSetViewTable'] = $this->getFactory()->createProductSetViewTable(
            $view->parent->vars['name']
        );
        $view->vars['tables']['productSetSelectedTable'] = $this->getFactory()->createProductSetSelectedTable(
            $view->vars['value']->getIdProductSet(),
            $view->parent->vars['name']
        );
        $view->vars['attr']['template_path'] = static::TEMPLATE_PATH;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'product-set';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdProductSetField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductSetField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_SET, HiddenType::class, [
            'label' => false,
        ])->get(static::FIELD_ID_PRODUCT_SET)->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event): void {
                if (!$event->getData()) {
                    return;
                }

                $id = (int)$event->getData();
                $event->setData($id);
                $event->getForm()->setData($id);
            }
        );

        return $this;
    }
}
