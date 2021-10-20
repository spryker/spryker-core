<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Communication\Form;

use ArrayObject;
use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinuedGui\Communication\ProductDiscontinuedGuiCommunicationFactory getFactory()
 */
class DiscontinueProductForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_DISCONTINUED_NOTES = 'discontinued_notes';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addDiscontinueNoteFormCollection($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDiscontinueNoteFormCollection(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DISCONTINUED_NOTES, CollectionType::class, [
            'entry_type' => ProductDiscontinuedNoteForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'data_class' => ProductDiscontinuedNoteTransfer::class,
            ],
        ]);

        $builder->get(static::FIELD_DISCONTINUED_NOTES)
            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createArrayObjectModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($value) {
                return (array)$value;
            },
            function ($value) {
                return new ArrayObject($value);
            },
        );
    }
}
