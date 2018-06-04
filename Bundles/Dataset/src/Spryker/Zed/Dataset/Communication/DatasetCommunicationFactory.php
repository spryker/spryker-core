<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication;

use Spryker\Zed\Dataset\Communication\Form\DataProvider\DatasetFormDataProvider;
use Spryker\Zed\Dataset\Communication\Form\DatasetForm;
use Spryker\Zed\Dataset\Communication\Form\DatasetLocalizedAttributesForm;
use Spryker\Zed\Dataset\Communication\Table\DatasetTable;
use Spryker\Zed\Dataset\DatasetDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Dataset\DatasetConfig getConfig()
 */
class DatasetCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Dataset\Communication\Table\DatasetTable
     */
    public function createDatasetTable()
    {
        return new DatasetTable($this->getRepository(), $this->getDatasetQuery());
    }

    /**
     * @param null|int $idDataset
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDatasetForm($idDataset = null)
    {
        $datasetFormProvider = $this->createDatasetFormDataProvider();

        return $this->getFormFactory()->create(
            DatasetForm::class,
            $datasetFormProvider->getData($idDataset),
            $datasetFormProvider->getOptions($idDataset)
        );
    }

    /**
     * @return string
     */
    public function getDatasetLocalizedAttributesForm()
    {
        return DatasetLocalizedAttributesForm::class;
    }

    /**
     * @return \Spryker\Zed\Dataset\Communication\Form\DataProvider\DatasetFormDataProvider
     */
    public function createDatasetFormDataProvider()
    {
        return new DatasetFormDataProvider($this->getRepository(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\Dataset\Dependency\Facade\DatasetToLocaleFacadeInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(DatasetDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function getDatasetQuery()
    {
        return $this->getProvidedDependency(DatasetDependencyProvider::PROPEL_DATASET_QUERY);
    }
}
