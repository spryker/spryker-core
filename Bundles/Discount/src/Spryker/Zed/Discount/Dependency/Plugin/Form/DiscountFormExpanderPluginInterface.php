<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin\Form;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Symfony\Component\Form\FormBuilderInterface;

interface DiscountFormExpanderPluginInterface
{

    const FORM_TYPE_CALCULATION = DiscountConfiguratorTransfer::DISCOUNT_CALCULATOR;
    const FORM_TYPE_GENERAL = DiscountConfiguratorTransfer::DISCOUNT_GENERAL;
    const FORM_TYPE_CONDITIONS = DiscountConfiguratorTransfer::DISCOUNT_CONDITION;

    /**
     * Specification:
     *
     * This method will received builder object from discount form type, you can use it to add new form types.
     * Or return new which for builder object instance.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandFormType(FormBuilderInterface $builder, array $options);

    /**
     * Specification:
     *
     * Expand data provider options, the options will be passed from concrete form provider that is (calculator, general, general...)
     *
     * @api
     *
     * @param array $options
     *
     * @return array
     */
    public function expandDataProviderOptions(array $options);

    /**
     * Specification:
     *
     * Expand data provider data, the options will be passed from concrete form provider that is (calculator, general, general...)
     *
     * @api
     *
     * @param array $data
     *
     * @return array
     */
    public function expandDataProviderData(array $data);

    /**
     * Specification:
     *
     * Is a id of form type in DiscountForm, can be any defined in this interface constants.
     * This will be used to identify which subform extend
     *
     * @api
     *
     * @return string
     */
    public function getFormTypeToExtend();

}
