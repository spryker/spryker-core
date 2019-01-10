<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderItemType extends AbstractType
{
    public const FIELD_ASSIGNED = 'assigned';
    public const ASSIGNED_ID_COLLECTION = 'assigned_id_collection';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'orderItem';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ItemTransfer::class,
            self::ASSIGNED_ID_COLLECTION => [],
        ));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $idAssignedCollection = (array) $builder->getOption(self::ASSIGNED_ID_COLLECTION);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($idAssignedCollection) {
                $orderItem = $event->getData();
                $form = $event->getForm();``

                $options = [
                    'mapped' => false,
                    'label' => false,
                    'required' => false,
                    'data' => $orderItem->getId(),
                ];

                if (in_array($orderItem->getId(), $idAssignedCollection)) {
                    $form->add(static::FIELD_ASSIGNED, HiddenType::class, $options);
                } else {
                    $form->add(static::FIELD_ASSIGNED, CheckboxType::class, $options);
                }
            }
        );
    }
}
