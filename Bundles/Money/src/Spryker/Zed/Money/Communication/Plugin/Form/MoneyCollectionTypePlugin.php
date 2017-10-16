<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Plugin\Form;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionTypeInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @method \Spryker\Zed\Money\Business\MoneyFacade getFacade()
 * @method \Spryker\Zed\Money\Communication\MoneyCommunicationFactory getFactory()
 */
class MoneyCollectionTypePlugin extends AbstractPlugin implements MoneyCollectionTypeInterface, FormTypeInterface
{

    /**
     * @var \Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionTypeInterface
     */
    protected $moneyValueCollectionType;

    public function __construct()
    {
        $this->moneyValueCollectionType = $this->getFactory()->createMoneyCollectionType();
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->moneyValueCollectionType->setDefaultOptions($resolver);
    }

    /**
     * @return string|\Symfony\Component\Form\FormTypeInterface
     */
    public function getParent()
    {
        return $this->moneyValueCollectionType->getParent();
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $this->moneyValueCollectionType->finishView($view, $form, $options);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->moneyValueCollectionType->getName();
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->moneyValueCollectionType->buildForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $this->moneyValueCollectionType->configureOptions($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $this->moneyValueCollectionType->buildView($view, $form, $options);
    }

}
