<?php

namespace SprykerFeature\Zed\Product\Business\Importer;

use SprykerFeature\Zed\Product\Business\Builder\ProductBuilderInterface;
use SprykerFeature\Zed\Product\Business\Model\ProductBatchResult;
use SprykerFeature\Zed\Product\Business\Importer\Reader\File;
use SprykerFeature\Shared\Product\Model\ProductInterface;
use SprykerFeature\Zed\Product\Business\Importer\Writer\ProductWriterInterface;
use SprykerFeature\Zed\Product\Business\Model\ProductBatchResultInterface;
use SprykerFeature\Zed\Product\Business\Validator\DataValidatorInterface;

/**
 * Class CsvImporter
 *
 * @package SprykerFeature\Zed\Product\Business\Importer
 */
class FileImporter
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
    private $productBatchResultPrototype;

    /**
     * @param DataValidatorInterface        $importProductValidator
     * @param File\IteratorReaderInterface  $reader
     * @param ProductBuilderInterface       $productBuilder
     * @param ProductWriterInterface        $writer
     * @param ProductBatchResultInterface   $productBatchResultPrototype
     */
    public function __construct(
        DataValidatorInterface $importProductValidator,
        File\IteratorReaderInterface $reader,
        ProductBuilderInterface $productBuilder,
        ProductWriterInterface $writer,
        ProductBatchResultInterface $productBatchResultPrototype
    ) {
        $this->importProductValidator = $importProductValidator;
        $this->fileReader = $reader;
        $this->productBuilder = $productBuilder;
        $this->productWriter = $writer;
        $this->productBatchResultPrototype = $productBatchResultPrototype;
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return ProductBatchResult
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

        $result = clone $this->productBatchResultPrototype;
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
            return null;
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return ProductInterface
     */
    protected function process(array $data)
    {
        return $this->productBuilder->buildProduct($data);
    }

    /**
     * @param ProductInterface $product
     *
     * @return bool
     */
    protected function afterProcess(ProductInterface $product)
    {
        return $this->productWriter->writeProduct($product);
    }

    /**
     * @param int $line
     */
    protected function logInvalidProduct($line)
    {
        $this->invalidProducts[$line] = 'invalid product';
    }

    /**
     * @param int $line
     */
    protected function logNotImportableProduct($line)
    {
        $this->invalidProducts[$line] = 'Not importable';
    }
}
