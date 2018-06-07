<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\Type;

use Generated\Shared\Transfer\ArgumentTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 */
class ArgumentType extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'argumentCollectionTransfer',
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $argumentCollectionTransfer = $options['argumentCollectionTransfer'];

        $builder->add('argument', ChoiceType::class, [
            'choices' => $argumentCollectionTransfer->getArguments(),
            'choice_label' => function (ArgumentTransfer $argumentTransfer) {
                return $argumentTransfer->getName();
            },
            'choice_attr' => function (ArgumentTransfer $argumentTransfer) {
                return ['data-proposal' => $argumentTransfer->getVariable()];
            },
            'placeholder' => '',
            'attr' => [
                'class' => 'type-selector',
            ],
        ]);
        $builder->add('variable', TextType::class);
        $builder->add('isOptional', CheckboxType::class, [
            'required' => false,
        ]);

//        $argumentCollectionTransfer = new ArgumentCollectionTransfer();
//        $builder->get()->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($argumentCollectionTransfer) {
//
//            $form = $event->getForm();
//            $parentForm = $form->getParent();
//            $parentFormData = $parentForm->getData();
//            if (!$parentFormData) {
//                $parentFormData = new ArgumentCollectionTransfer();
//                $parentForm->setData($parentFormData);
//            }
//            $argumentData = $event->getData();
//
//            $argumentTransfer = $argumentData['type'];
//            $argumentTransfer->setVariable($argumentData['variable']);
//            $argumentTransfer->setIsOptional($argumentData['isOptional']);
//
//            $parentFormData->addArgument($argumentTransfer);
//        });
    }
}
