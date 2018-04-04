<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDataImport\Business;

use Spryker\Zed\CategoryDataImport\Business\Model\CategoryWriterStep;
use Spryker\Zed\CategoryDataImport\Business\Model\Reader\CategoryReader;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryDataImport\CategoryDataImportConfig getConfig()
 */
class CategoryDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCategoryImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCategoryDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createLocalizedAttributesExtractorStep([
                CategoryWriterStep::KEY_NAME,
                CategoryWriterStep::KEY_META_TITLE,
                CategoryWriterStep::KEY_META_DESCRIPTION,
                CategoryWriterStep::KEY_META_KEYWORDS,
            ]))
            ->addStep(new CategoryWriterStep($this->createCategoryRepository()));

        $dataImporter
            ->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\CategoryDataImport\Business\Model\Reader\CategoryReaderInterface
     */
    protected function createCategoryRepository()
    {
        return new CategoryReader();
    }
}
