<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Exporter;

use Orm\Zed\Locale\Persistence\Base\SpyLocale;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Spryker\Shared\FactFinder\FactFinderConstants;
use Spryker\Zed\FactFinder\Business\Writer\AbstractFileWriter;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\FactFinder\Business\FactFinderBusinessFactory getFactory()
 * @method \Spryker\Zed\FactFinder\Business\FactFinderFacade getFacade()
 */
class FactFinderProductExporterPlugin extends AbstractPlugin
{

    /**
     * @var AbstractFileWriter
     */
    protected $fileWriter;

    /**
     * @var SpyLocale
     */
    protected $locale;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var string
     */
    protected $queryLimit;

    /**
     * @var string
     */
    protected $fileNamePrefix;

    /**
     * @var string
     */
    protected $fileNameDelimiter;

    /**
     * @var string
     */
    protected $fileExtension;

    /**
     * FactFinderProductExporterPlugin constructor.
     *
     * @param AbstractFileWriter $fileWriter
     * @param SpyLocale $locale
     */
    public function __construct(AbstractFileWriter $fileWriter, SpyLocale $locale)
    {
        $config = $this->getFactory()
            ->getConfig();
        $this->fileWriter = $fileWriter;
        $this->locale = $locale;
        $this->queryLimit = $config->getExportQueryLimit();
        $this->fileNamePrefix = $config->getExportFileNamePrefix();
        $this->fileNameDelimiter = $config->getExportFileNameDelimiter();
        $this->fileExtension = $config->getExportFileExtension();
    }

    /**
     * @return void
     */
    public function export()
    {
        $query = $this->getFactory()
            ->getFactFinderQueryContainer()
            ->getExportDataQuery($this->locale->getIdLocale());

        if (!$this->productsExists($query)) {
            return;
        }
        $filePath = $this->getFilePath($this->locale->getLocaleName());

        $this->exportToCsv($filePath, $query);
    }

    /**
     * @param string $filePath
     * @param SpyProductAbstractQuery $query
     */
    protected function exportToCsv($filePath, SpyProductAbstractQuery $query)
    {
        $offset = 0;
        $this->saveFileHeader($filePath);

        do {
            $result = $query->limit($this->queryLimit)
                ->offset($offset)
                ->find()
                ->toArray();
            $offset += $this->queryLimit;

            $prepared = $this->prepareDataForExport($result, $this->locale);

            $this->fileWriter
                ->write($filePath,$prepared, true);
        } while (!empty($result));
    }

    /**
     * @param SpyProductAbstractQuery $query
     *
     * @return bool
     */
    protected function productsExists(SpyProductAbstractQuery $query)
    {
        $productsCount = $query->limit($this->queryLimit)
            ->count();
        return $productsCount > 0;
    }

    /**
     * @return array
     */
    protected function getFileHeader()
    {
        return FactFinderConstants::ITEM_FIELDS;
    }

    /**
     * @param $localeName
     *
     * @return string
     */
    protected function getFilePath($localeName)
    {
        $directory = $this->getConfig()->getCsvDirectory();
        $fileName = $this->fileNamePrefix . $this->fileNameDelimiter . $localeName . $this->fileExtension;

        return $directory . $fileName;
    }

    /**
     * @param $data array
     * @param SpyLocale $locale
     *
     * @return array
     */
    protected function prepareDataForExport($data, SpyLocale $locale)
    {
        $headers = $this->getFileHeader();
        $dataForExport = [];

        foreach ($data as $row) {
            $prepared = [];
            $row = $this->addProductUrl($row);
            $row = $this->addCategoryPath($row, $locale);

            foreach ($headers as $headerName) {
                if (isset($row[$headerName])) {
                    $prepared[$headerName] = $row[$headerName];
                }
            }

            $dataForExport[] = $prepared;
        }

        return $dataForExport;
    }

    /**
     * @param $data array
     *
     * @return array
     */
    protected function addProductUrl($data)
    {
        $productUrl = "/fact-finder/detail/{$data[FactFinderConstants::ITEM_PRODUCT_NUMBER]}";
        $data[FactFinderConstants::ITEM_PRODUCT_URL] = $productUrl;

        return $data;
    }

    /**
     * @param $data array
     * @param SpyLocale $locale
     *
     * @return array
     */
    protected function addCategoryPath($data, SpyLocale $locale)
    {
        $parentCategoryName = $this->getParentСategoryName(
            $locale->getIdLocale(),
            $data[FactFinderConstants::ITEM_PARENT_CATEGORY_NODE_ID]
        );

        if (empty($parentCategoryName)) {
            $categoryPath = $data[FactFinderConstants::ITEM_CATEGORY];
        } else {
            $categoryPath = $parentCategoryName . '/' . $data[FactFinderConstants::ITEM_CATEGORY];
        }
        $data[FactFinderConstants::ITEM_CATEGORY_PATH] = $categoryPath ;

        return $data;
    }

    /**
     * @param $idLocale int
     * @param $rootCategoryNodeId int
     *
     * @return string
     */
    protected function getParentСategoryName($idLocale, $rootCategoryNodeId)
    {
        $query = $this->getFacade()
            ->getParentCategoryQuery($idLocale, $rootCategoryNodeId);
        $category = $query->findOne();

        if (empty($category)) {
            return '';
        }

        return $category->getName();
    }

    /**
     * @param string $filePath
     */
    protected function saveFileHeader($filePath)
    {
        $header = $this->getFileHeader();
        $this->fileWriter->write($filePath, [$header]);
    }

}