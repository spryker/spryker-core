<?php

namespace Spryker\Zed\FileManagerGui\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileForm extends AbstractType
{
    const FIELD_FILE_NAME = 'fileName';
    const FIELD_FILE_CONTENT = 'fileContent';
    const FIELD_ID_FILE = 'idFile';

    /**
     * @return string
     */
    public function getName()
    {
        return 'file';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addFileNameField($builder)
            ->addIdFileField($builder)
            ->addFileContentField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFileNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FILE_NAME, TextType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdFileField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_FILE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFileContentField(FormBuilderInterface $builder)
    {
        $formData = $builder->getData();
        $builder->add(static::FIELD_FILE_CONTENT, FileType::class, [
            'required' => empty($formData[static::FIELD_ID_FILE]),
        ]);

        return $this;
    }

}
