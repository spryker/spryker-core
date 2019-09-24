<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\FormExpander;

use Symfony\Component\Form\FormBuilderInterface;

class ProductListMerchantRelationshipCreateFormExpander extends ProductListMerchantRelationshipEditFormExpander implements ProductListMerchantRelationshipCreateFormExpanderInterface
{
    /**
     * @uses \Spryker\Zed\MerchantRelationshipGui\Communication\Form\MerchantRelationshipCreateForm::OPTION_SELECTED_COMPANY
     */
    protected const OPTION_SELECTED_COMPANY = 'id_company';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function expand(FormBuilderInterface $builder, array $options): void
    {
        if (!$options[static::OPTION_SELECTED_COMPANY]) {
            return;
        }

        parent::expand($builder, $options);
    }
}
