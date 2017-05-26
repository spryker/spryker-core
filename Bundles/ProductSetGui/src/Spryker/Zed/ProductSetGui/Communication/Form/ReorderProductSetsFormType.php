<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class ReorderProductSetsFormType extends AbstractType
{

    const FIELD_PRODUCT_SET_WEIGHTS = 'product_set_weights';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addProductOrderField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductOrderField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_PRODUCT_SET_WEIGHTS, HiddenType::class);

        $builder->get(self::FIELD_PRODUCT_SET_WEIGHTS)
            ->addModelTransformer(new CallbackTransformer(
                function ($productSetIds = null) {
                    return json_encode((array)$productSetIds); // FIXME
                },
                function ($productSetIds = '{}') {
                    return json_decode($productSetIds, true);
                }
            ));

        return $this;
    }

}
