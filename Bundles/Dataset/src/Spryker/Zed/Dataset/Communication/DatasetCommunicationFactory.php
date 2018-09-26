<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication;

use Orm\Zed\Dataset\Persistence\SpyDatasetQuery;
use Spryker\Zed\Dataset\Communication\Form\DataProvider\DatasetFormDataProvider;
use Spryker\Zed\Dataset\Communication\Form\DatasetForm;
use Spryker\Zed\Dataset\Communication\Form\DatasetLocalizedAttributesForm;
use Spryker\Zed\Dataset\Communication\Table\DatasetTable;
use Spryker\Zed\Dataset\DatasetDependencyProvider;
use Spryker\Zed\Dataset\Dependency\Facade\DatasetToLocaleFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\Dataset\DatasetConfig getConfig()
 * @method \Spryker\Zed\Dataset\Persistence\DatasetRepository getRepository()
 */
class DatasetCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Dataset\Communication\Table\DatasetTable
     */
    public function createDatasetTable(): DatasetTable
    {
        return new DatasetTable($this->getRepository(), $this->getDatasetQuery());
    }

    /**
     * @param int|null $idDataset
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getDatasetForm(?int $idDataset = null): FormInterface
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
    public function getDatasetLocalizedAttributesForm(): string
    {
        return DatasetLocalizedAttributesForm::class;
    }

    /**
     * @return \Spryker\Zed\Dataset\Communication\Form\DataProvider\DatasetFormDataProvider
     */
    public function createDatasetFormDataProvider(): DatasetFormDataProvider
    {
        return new DatasetFormDataProvider($this->getRepository(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\Dataset\Dependency\Facade\DatasetToLocaleFacadeInterface
     */
    public function getLocaleFacade(): DatasetToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(DatasetDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function getDatasetQuery(): SpyDatasetQuery
    {
        return $this->getProvidedDependency(DatasetDependencyProvider::PROPEL_DATASET_QUERY);
    }
}
