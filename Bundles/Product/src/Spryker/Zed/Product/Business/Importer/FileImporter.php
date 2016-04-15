<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Importer;

use Spryker\Shared\Library\BatchIterator\CsvBatchIterator;
use Spryker\Zed\Product\Business\Builder\ProductBuilderInterface;
use Spryker\Zed\Product\Business\Importer\Writer\ProductWriterInterface;
use Spryker\Zed\Product\Business\Model\ProductBatchResultInterface;
use Spryker\Zed\Product\Business\Validator\DataValidatorInterface;

class FileImporter implements FileImporterInterface
{

    /**
     * @var \Spryker\Zed\Product\Business\Validator\DataValidatorInterface
     */
    protected $importProductValidator;

    /**
     * @var \Spryker\Zed\Product\Business\Builder\ProductBuilderInterface
     */
    protected $productBuilder;

    /**
     * @var \Spryker\Zed\Product\Business\Importer\Writer\ProductWriterInterface
     */
    protected $productWriter;

    /**
     * @var array
     *
     * @todo improve logging
     */
    private $invalidProducts = [];

    /**
     * @var \Spryker\Zed\Product\Business\Model\ProductBatchResultInterface
     */
    private $productBatchResult;

    /**
     * @param \Spryker\Zed\Product\Business\Validator\DataValidatorInterface $importProductValidator
     * @param \Spryker\Zed\Product\Business\Builder\ProductBuilderInterface $productBuilder
     * @param \Spryker\Zed\Product\Business\Importer\Writer\ProductWriterInterface $writer
     * @param \Spryker\Zed\Product\Business\Model\ProductBatchResultInterface $productBatchResult
     */
    public function __construct(
        DataValidatorInterface $importProductValidator,
        ProductBuilderInterface $productBuilder,
        ProductWriterInterface $writer,
        ProductBatchResultInterface $productBatchResult
    ) {
        $this->importProductValidator = $importProductValidator;
        $this->productBuilder = $productBuilder;
        $this->productWriter = $writer;
        $this->productBatchResult = $productBatchResult;
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return \Spryker\Zed\Product\Business\Model\ProductBatchResult
     */
    public function importFile(\SplFileInfo $file)
    {
        $totalCount = 0;
        $batchIterator = new CsvBatchIterator($file->getRealPath(), 10);

        foreach ($batchIterator as $batchCollection) {
            foreach ($batchCollection as $productDataToImport) {
                $preProcessedProduct = $this->preProcess($productDataToImport);

                if (empty($preProcessedProduct)) {
                    $this->logInvalidProduct($productDataToImport);
                    continue; //skip invalid products
                }

                $product = $this->process($preProcessedProduct);
                $result = $this->afterProcess($product);
                $totalCount++;

                if (!$result) {
                    $this->logNotImportableProduct($productDataToImport);
                }
            }
        }

        $result = clone $this->productBatchResult;
        $result->setTotalCount($totalCount);
        $result->setFailedCount(count($this->invalidProducts));

        return $result;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function preProcess(array $data)
    {
        if (!$this->importProductValidator->isValid($data)) {
            return [];
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|\Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function process(array $data)
    {
        return $this->productBuilder->buildProduct($data);
    }

    /**
     * @param \Spryker\Shared\Product\Model\ProductAbstractInterface $product
     *
     * @return bool
     */
    protected function afterProcess($product)
    {
        return $this->productWriter->writeProduct($product);
    }

    /**
     * @param int $line
     *
     * @return void
     */
    protected function logInvalidProduct($line)
    {
        $this->invalidProducts[$line] = 'invalid product';
    }

    /**
     * @param int $line
     *
     * @return void
     */
    protected function logNotImportableProduct($line)
    {
        $this->invalidProducts[$line] = 'Not importable';
    }

}
