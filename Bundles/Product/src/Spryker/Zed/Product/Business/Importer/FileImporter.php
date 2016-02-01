<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Importer;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Builder\ProductBuilderInterface;
use Spryker\Zed\Product\Business\Model\ProductBatchResult;
use Spryker\Zed\Product\Business\Importer\Reader\File;
use Spryker\Zed\Product\Business\Importer\Writer\ProductWriterInterface;
use Spryker\Zed\Product\Business\Model\ProductBatchResultInterface;
use Spryker\Zed\Product\Business\Validator\DataValidatorInterface;

class FileImporter implements FileImporterInterface
{

    /**
     * @var DataValidatorInterface
     */
    protected $importProductValidator;

    /**
     * @var File\IteratorReaderInterface
     */
    protected $fileReader;

    /**
     * @var ProductBuilderInterface
     */
    protected $productBuilder;

    /**
     * @var ProductWriterInterface
     */
    protected $productWriter;

    /**
     * @var array
     *
     * @todo improve logging
     */
    private $invalidProducts = [];

    /**
     * @var ProductBatchResultInterface
     */
    private $productBatchResult;

    /**
     * @param DataValidatorInterface $importProductValidator
     * @param File\IteratorReaderInterface $reader
     * @param ProductBuilderInterface $productBuilder
     * @param ProductWriterInterface $writer
     * @param ProductBatchResultInterface $productBatchResult
     */
    public function __construct(
        DataValidatorInterface $importProductValidator,
        File\IteratorReaderInterface $reader,
        ProductBuilderInterface $productBuilder,
        ProductWriterInterface $writer,
        ProductBatchResultInterface $productBatchResult
    ) {
        $this->importProductValidator = $importProductValidator;
        $this->fileReader = $reader;
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
        $rows = $this->fileReader->getIteratorFromFile($file);
        $fieldKeys = $rows->current();

        while (!$rows->eof()) {
            $line = $rows->key();
            if ($line > 0) {
                $row = array_combine($fieldKeys, $rows->current());
                $preProcessedProduct = $this->preProcess($row);

                if (empty($preProcessedProduct)) {
                    $this->logInvalidProduct($line);
                    continue; //skip invalid products
                }

                $product = $this->process($preProcessedProduct);
                $result = $this->afterProcess($product);

                if (!$result) {
                    $this->logNotImportableProduct($line);
                }
            }
            $rows->next();
        }

        $result = clone $this->productBatchResult;
        $result->setTotalCount($rows->key() - 1);
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
            return;
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return ProductAbstractTransfer|ProductConcreteTransfer
     */
    protected function process(array $data)
    {
        return $this->productBuilder->buildProduct($data);
    }

    /**
     * @param $product
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
