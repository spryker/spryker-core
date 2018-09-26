<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductSetGui\Communication\ProductSetGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface getQueryContainer()
 */
class ReorderProductSetsFormType extends AbstractType
{
    public const FIELD_PRODUCT_SET_WEIGHTS = 'product_set_weights';

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
        $builder->add(static::FIELD_PRODUCT_SET_WEIGHTS, HiddenType::class);

        $builder->get(static::FIELD_PRODUCT_SET_WEIGHTS)
            ->addModelTransformer(new CallbackTransformer(
                function ($productSetIds = null) {
                    return $this->getFactory()->getUtilEncodingService()->encodeJson((array)$productSetIds);
                },
                function ($productSetIds = '{}') {
                    return $this->getFactory()->getUtilEncodingService()->decodeJson($productSetIds, true);
                }
            ));

        return $this;
    }
}
