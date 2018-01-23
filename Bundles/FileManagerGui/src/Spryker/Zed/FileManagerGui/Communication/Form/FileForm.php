<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FileForm extends AbstractType
{
    const FIELD_FILE_NAME = 'fileName';
    const FIELD_FILE_CONTENT = 'fileContent';
    const FIELD_ID_FILE = 'idFile';
    const FIELD_USE_REAL_NAME = 'useRealName';

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
            ->addUseRealNameOption($builder)
            ->addFileContentField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFileNameField(FormBuilderInterface $builder)
    {
        $formData = $builder->getData();
        $builder->add(static::FIELD_FILE_NAME, TextType::class, [
            'required' => !empty($formData[static::FIELD_USE_REAL_NAME]),
        ]);

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

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUseRealNameOption(FormBuilderInterface $builder)
    {
        $formData = $builder->getData();

        if (empty($formData[static::FIELD_ID_FILE])) {
            $builder->add(static::FIELD_USE_REAL_NAME, CheckboxType::class, [
                'attr' => [
                    'checked' => 'checked',
                ],
                'required' => false,
            ]);
        }

        return $this;
    }
}
