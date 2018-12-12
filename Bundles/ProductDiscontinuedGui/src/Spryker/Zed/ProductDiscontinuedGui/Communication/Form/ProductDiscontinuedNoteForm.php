<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinuedGui\Communication\ProductDiscontinuedGuiCommunicationFactory getFactory()
 */
class ProductDiscontinuedNoteForm extends AbstractType
{
    public const FIELD_FK_LOCALE = 'fkLocale';
    public const FIELD_NOTE = 'note';
    public const FIELD_FK_PRODUCT_DISCONTINUED = 'fkProductDiscontinued';
    public const FIELD_ID_PRODUCT_DISCONTINUED_NOTE = 'idProductDiscontinuedNote';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNoteField($builder)
            ->addFkLocaleField($builder)
            ->addFkProductDiscontinuedField($builder)
            ->addIdProductDiscontinuedNoteField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNoteField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_NOTE, TextType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkLocaleField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_FK_LOCALE, HiddenType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkProductDiscontinuedField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_FK_PRODUCT_DISCONTINUED, HiddenType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductDiscontinuedNoteField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_PRODUCT_DISCONTINUED_NOTE, HiddenType::class, [
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }
}
