<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSearch\Communication\Form\SearchPreferencesForm;
use Spryker\Zed\ProductSearch\Communication\Table\SearchPreferencesTable;

/**
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductSearch\ProductSearchConfig getConfig()
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacade getFacade()
 */
class ProductSearchCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\ProductSearch\Communication\Table\SearchPreferencesTable
     */
    public function createSearchPreferencesTable()
    {
        return new SearchPreferencesTable();
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSearchPreferencesForm(array $data = [], array $options = [])
    {
        $filterFormType = new SearchPreferencesForm();

        return $this->getFormFactory()->create($filterFormType, $data, $options);
    }

}
