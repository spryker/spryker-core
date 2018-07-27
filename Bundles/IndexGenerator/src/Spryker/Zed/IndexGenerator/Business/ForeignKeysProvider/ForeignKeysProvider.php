<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business\ForeignKeysProvider;

use Generated\Shared\Transfer\ForeignKeyFileTransfer;
use Generated\Shared\Transfer\ForeignKeyTableTransfer;
use SimpleXMLElement;
use Spryker\Zed\IndexGenerator\Business\SchemaFinder\MergedSchemaFinderInterface;
use Symfony\Component\Finder\SplFileInfo;

class ForeignKeysProvider implements ForeignKeysProviderInterface
{
    /**
     * @var \Spryker\Zed\IndexGenerator\Business\SchemaFinder\MergedSchemaFinderInterface
     */
    protected $finder;

    /**
     * @var string[]
     */
    protected $excludedTables;

    /**
     * @param \Spryker\Zed\IndexGenerator\Business\SchemaFinder\MergedSchemaFinderInterface $finder
     * @param array $excludedTables
     */
    public function __construct(MergedSchemaFinderInterface $finder, array $excludedTables)
    {
        $this->finder = $finder;
        $this->excludedTables = $excludedTables;
    }

    /**
     * @return \Generated\Shared\Transfer\ForeignKeyFileTransfer[]
     */
    public function getForeignKeyList(): array
    {
        $foreignKeyFileTransferCollection = [];

        foreach ($this->finder->findMergedSchemas() as $fileInfo) {
            $foreignKeyFileTransfer = $this->processEntityDefinitionFile($fileInfo);
            if ($foreignKeyFileTransfer !== null) {
                $foreignKeyFileTransferCollection[] = $foreignKeyFileTransfer;
            }
        }

        return $foreignKeyFileTransferCollection;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return \Generated\Shared\Transfer\ForeignKeyFileTransfer|null
     */
    protected function processEntityDefinitionFile(SplFileInfo $fileInfo): ?ForeignKeyFileTransfer
    {
        $xmlElement = simplexml_load_string($fileInfo->getContents());
        $foreignKeyFileTransfer = $this->createForeignKeyFileTransfer($xmlElement, $fileInfo);

        foreach ($this->getTableXmlElements($xmlElement) as $tableXmlElement) {
            if ($this->isTableExcluded($tableXmlElement)) {
                continue;
            }
            $foreignKeyTableTransfer = $this->processTableXmlElement($tableXmlElement);

            if ($foreignKeyTableTransfer->getColumns() !== null) {
                $foreignKeyFileTransfer->addForeignKeyTable($foreignKeyTableTransfer);
            }
        }

        return $foreignKeyFileTransfer;
    }

    /**
     * @param \SimpleXMLElement $tableXmlElement
     *
     * @return bool
     */
    protected function isTableExcluded(SimpleXMLElement $tableXmlElement): bool
    {
        $tableName = (string)$tableXmlElement['name'];
        if (in_array($tableName, $this->excludedTables, true)) {
            return true;
        }

        return false;
    }

    /**
     * @param \SimpleXMLElement $tableXmlElement
     *
     * @return \Generated\Shared\Transfer\ForeignKeyTableTransfer
     */
    protected function processTableXmlElement(SimpleXMLElement $tableXmlElement): ForeignKeyTableTransfer
    {
        $foreignKeyTableTransfer = new ForeignKeyTableTransfer();
        $foreignKeyTableTransfer->setTableName((string)$tableXmlElement['name']);

        if (!$this->isIndexable($tableXmlElement)) {
            return $foreignKeyTableTransfer;
        }

        $foreignKeyColumnNames = $this->getForeignKeyColumnNames($tableXmlElement);
        $indexedColumnNames = $this->getIndexedColumnNames($tableXmlElement);
        foreach ($foreignKeyColumnNames as $foreignKeyColumnName) {
            if (in_array($foreignKeyColumnName, $indexedColumnNames, true)) {
                continue;
            }
            $foreignKeyTableTransfer->addColumn($foreignKeyColumnName);
        }

        return $foreignKeyTableTransfer;
    }

    /**
     * @param \SimpleXMLElement $tableXmlElement
     *
     * @return bool
     */
    protected function isIndexable(SimpleXMLElement $tableXmlElement): bool
    {
        return ($tableXmlElement->xpath('behavior[@name="archivable"]') === false || count($tableXmlElement->xpath('behavior[@name="archivable"]')) === 0);
    }

    /**
     * @param \SimpleXMLElement $tableXmlElement
     *
     * @return array
     */
    protected function getForeignKeyColumnNames(SimpleXMLElement $tableXmlElement): array
    {
        $foreignKeyColumnNames = [];
        foreach ($tableXmlElement->xpath('foreign-key/reference') as $reference) {
            $foreignKeyColumnNames[] = (string)$reference['local'];
        }

        return $foreignKeyColumnNames;
    }

    /**
     * @param \SimpleXMLElement $tableXmlElement
     *
     * @return array
     */
    protected function getIndexedColumnNames(SimpleXMLElement $tableXmlElement): array
    {
        $indexedColumnNames = [];
        foreach ($tableXmlElement->xpath('index/index-column') as $indexColumn) {
            $indexedColumnNames[] = (string)$indexColumn['name'];
        }

        return $indexedColumnNames;
    }

    /**
     * @param \SimpleXMLElement $xmlElement
     *
     * @return \SimpleXMLElement[]
     */
    protected function getTableXmlElements(SimpleXMLElement $xmlElement): array
    {
        return $xmlElement->xpath('//table') ?: [];
    }

    /**
     * @param \SimpleXMLElement $xmlElement
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return \Generated\Shared\Transfer\ForeignKeyFileTransfer
     */
    protected function createForeignKeyFileTransfer(SimpleXMLElement $xmlElement, SplFileInfo $fileInfo): ForeignKeyFileTransfer
    {
        $foreignKeyFileTransfer = new ForeignKeyFileTransfer();

        $database = $xmlElement->xpath('//database')[0] ?: [];

        $foreignKeyFileTransfer->setNamespace((string)$database['namespace']);
        $foreignKeyFileTransfer->setPackage((string)$database['package']);
        $foreignKeyFileTransfer->setFilename($fileInfo->getFilename());

        return $foreignKeyFileTransfer;
    }
}
