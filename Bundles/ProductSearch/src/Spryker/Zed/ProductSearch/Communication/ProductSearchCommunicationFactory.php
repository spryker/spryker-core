<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSearch\Communication\Form\FilterForm;
use Spryker\Zed\ProductSearch\Communication\Table\FiltersTable;
use Spryker\Zed\ProductSearch\Communication\Table\SearchTable;

/**
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductSearch\ProductSearchConfig getConfig()
 */
class ProductSearchCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Storage\Communication\Table\StorageTable
     */
    public function createSearchTable()
    {
        return new SearchTable($this->getFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Communication\Table\FiltersTable
     */
    public function createFiltersTable()
    {
        return new FiltersTable();
    }

    /**
     * @param array $data
     * @param array $options
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFilterForm(array $data = [], array $options = [])
    {
        $filterFormType = new FilterForm();

        return $this->getFormFactory()->create($filterFormType, $data, $options);
    }

}
