<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOption\Communication\Form\GeneralForm;
use Spryker\Zed\ProductOption\Communication\Form\ProductOptionForm;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 */
class ProductOptionCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductOptionForm()
    {
        $productOptionFormType = new ProductOptionForm();

        return $this->getFormFactory()->create(
            $productOptionFormType,
            null
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createGeneralForm()
    {
        $generalFormType = new GeneralForm();

        return $this->getFormFactory()->create(
            $generalFormType,
            null
        );
    }
}
