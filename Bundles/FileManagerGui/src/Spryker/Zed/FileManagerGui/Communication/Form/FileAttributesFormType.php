<?php

namespace Spryker\Zed\FileManagerGui\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FileAttributesFormType extends AbstractType
{
    const FIELD_TITLE = 'title';
    const FIELD_ALT = 'alt';
    const FIELD_ID_FILE_LOCALIZED_ATTRIBUTES = 'idFileLocalizedAttributes';
    const FIELD_LOCALE_NAME = 'localeName';

    const OPTION_AVAILABLE_LOCALES = 'option_available_locales';

    public function __construct() {}

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addTitleField($builder)
            ->addAltField($builder)
            ->addFileLocaleNameField($builder)
            ->addFileLocalizedAttributes($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setRequired(static::OPTION_AVAILABLE_LOCALES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTitleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TITLE, TextType::class, [
            'label' => 'Title',
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAltField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ALT, TextType::class, [
            'label' => 'Alt',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFileLocaleNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LOCALE_NAME, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFileLocalizedAttributes(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_FILE_LOCALIZED_ATTRIBUTES, HiddenType::class);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'file_attributes';
    }

}
