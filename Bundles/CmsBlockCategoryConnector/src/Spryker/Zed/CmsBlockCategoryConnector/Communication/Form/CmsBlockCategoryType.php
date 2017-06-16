<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Form;


use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsBlockCategoryType extends AbstractType
{
    const FIELD_ID_CMS_BLOCK = 'id_cms_block';
    const FIELD_CATEGORIES = 'categories';

    const OPTION_CATEGORIES = 'option-category';

    /**
     * @return string
     */
    public function getName()
    {
        return 'categories';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCategoriesField($builder, $options[static::OPTION_CATEGORIES]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_CATEGORIES);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addCategoriesField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_CATEGORIES, new Select2ComboBoxType(), [
            'label' => 'Categories',
            'choices' => $choices,
            'multiple' => true,
            'required' => false,
        ]);

        return $this;
    }

}