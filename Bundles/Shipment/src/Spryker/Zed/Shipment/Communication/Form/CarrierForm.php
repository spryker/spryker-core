<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CarrierForm extends AbstractType
{

    const FIELD_NAME_GLOSSARY_FIELD = 'glossaryKeyName';
    const FIELD_NAME_FIELD = 'name';
    const FIELD_IS_ACTIVE_FIELD = 'isActive';

    /**
     * @return string
     */
    public function getName()
    {
        return 'carrier';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::FIELD_NAME_FIELD, 'text', [
                'label' => 'Name',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add(self::FIELD_NAME_GLOSSARY_FIELD, new AutosuggestType(), [
                'label' => 'Name glossary key',
                'url' => '/glossary/key/suggest',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add(self::FIELD_IS_ACTIVE_FIELD, 'checkbox', [
                'label' => 'Enabled?',
            ]);
    }

}
