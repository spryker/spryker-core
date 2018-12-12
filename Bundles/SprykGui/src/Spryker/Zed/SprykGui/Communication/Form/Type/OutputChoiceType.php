<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\Type;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \Spryker\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class OutputChoiceType extends AbstractType
{
    protected const OUTPUT_CHOICES = 'outputChoices';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            static::OUTPUT_CHOICES,
        ]);

        $resolver->setDefaults([
            'choices' => function (Options $options) {
                return $options[static::OUTPUT_CHOICES];
            },
            'choice_label' => 'type',
            'choice_value' => 'type',
        ]);
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
