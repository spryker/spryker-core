<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentItem\Form;

use Generated\Shared\Transfer\ContentAbstractProductTransfer;
use Spryker\Zed\ContentExtension\ContentTermFormInterface;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class AbstractProductListContentTermForm extends AbstractType implements ContentTermFormInterface
{
    public const FIELD_SKUS = 'skus';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'abstract-product-list';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addSkusField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkusField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_SKUS, CollectionType::class, [
            'entry_type' => AbstractProductContentTermForm::class,
            'label' => false,
            'entry_options' => [
                'label' => false,
                'data_class' => ContentAbstractProductTransfer::class,
            ],
        ]);

        return $this;
    }
}
