<?php
/**
 * Created by PhpStorm.
 * User: vsevoloddolgopolov
 * Date: 03/07/15
 * Time: 15:01
 */

namespace SprykerFeature\Zed\Product\Communication\Form\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\ValueToDuplicatesTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

class AutosuggestType extends AbstractType
{

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'autosuggest';
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['url'] = $options['url'];
        $view->vars['placeholder'] = $options['placeholder'];
    }


    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'url' => '/product/product/',
            'placeholder' => 'Select titles',
        ]);
    }
}
