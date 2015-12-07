<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AutosuggestType extends AbstractType
{

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['url'] = $options['url'];
        $view->vars['placeholder'] = $options['placeholder'];
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'url' => '',
            'placeholder' => 'Select value',
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'autosuggest';
    }

}
