<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UpdateNavigationFormType extends NavigationFormType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addKeyField(FormBuilderInterface $builder)
    {
        $builder
            ->add(self::FIELD_KEY, TextType::class, [
                'label' => 'Key',
                'attr' => [
                    'readonly' => 'readonly',
                ],
            ]);

        return $this;
    }
}
