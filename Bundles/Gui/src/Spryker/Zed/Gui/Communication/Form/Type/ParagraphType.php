<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Form\Type;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class ParagraphType extends AbstractType
{
    public const OPTION_TEXT = 'text';

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars[static::OPTION_TEXT] = $options[static::OPTION_TEXT];
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            static::OPTION_TEXT,
        ]);

        $resolver->setDefaults([
            static::OPTION_TEXT => 'Label text',
            'label' => false,
            'mapped' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'label';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
