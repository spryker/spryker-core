<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\FormattedNumberType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductCategory\ProductCategoryConfig getConfig()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface getQueryContainer()
 */
class OrderForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ORDER = 'order';

    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @var string
     */
    public const OPTION_ID = 'id';

    /**
     * @var string
     */
    public const OPTION_DATA_INFO = 'data-info';

    /**
     * @var string
     */
    protected const ATTR_SIZE = 'size';

    /**
     * @var string
     */
    protected const ATTR_CLASS = 'class';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addOrderField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::OPTION_LOCALE => null,
            static::OPTION_ID => null,
            static::OPTION_DATA_INFO => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addOrderField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ORDER, FormattedNumberType::class, [
            'label' => false,
            'locale' => $options[static::OPTION_LOCALE],
            'required' => false,
            'attr' => [
                static::OPTION_ID => $options[static::OPTION_ID],
                static::OPTION_DATA_INFO => $options[static::OPTION_DATA_INFO],
                static::ATTR_SIZE => 4,
                static::ATTR_CLASS => 'product_category_order',
            ],
        ]);

        return $this;
    }
}
