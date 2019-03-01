<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business\ForeignKeysProvider;

use Generated\Shared\Transfer\ForeignKeyFileTransfer;
use Generated\Shared\Transfer\ForeignKeyTableTransfer;
use SimpleXMLElement;
use Spryker\Zed\IndexGenerator\Business\Exception\FailedToLoadXmlException;
use Spryker\Zed\IndexGenerator\Business\Exception\IndexGeneratorException;
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
        $simpleXmlElement = $this->loadSimpleXmlElement($fileInfo);
        $hasNamespace = $this->hasNamespaceInSchema($simpleXmlElement);

        $foreignKeyFileTransfer = $this->createForeignKeyFileTransfer($simpleXmlElement, $fileInfo, $hasNamespace);

        foreach ($this->getTableXmlElements($simpleXmlElement, $hasNamespace) as $tableXmlElement) {
            if ($this->isTableExcluded($tableXmlElement)) {
                continue;
            }
            $foreignKeyTableTransfer = $this->processTableXmlElement($tableXmlElement, $hasNamespace);

            if (count($foreignKeyTableTransfer->getColumns()) > 0) {
                $foreignKeyFileTransfer->addForeignKeyTable($foreignKeyTableTransfer);
            }
        }

        return $foreignKeyFileTransfer;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @throws \Spryker\Zed\IndexGenerator\Business\Exception\FailedToLoadXmlException
     *
     * @return \SimpleXMLElement
     */
    protected function loadSimpleXmlElement(SplFileInfo $fileInfo): SimpleXMLElement
    {
        $simpleXmlElement = simplexml_load_string($fileInfo->getContents());
        if ($simpleXmlElement === false) {
            throw new FailedToLoadXmlException(sprintf('Could not load xml from file "%s"', $fileInfo->getFilename()));
        }

        return $simpleXmlElement;
    }

    /**
     * @param \SimpleXMLElement $simpleXmlElement
     *
     * @throws \Spryker\Zed\IndexGenerator\Business\Exception\IndexGeneratorException
     *
     * @return bool
     */
    protected function hasNamespaceInSchema(SimpleXMLElement $simpleXmlElement): bool
    {
        $result = $simpleXmlElement->xpath('//database');

        if ($result === false) {
            throw new IndexGeneratorException('Could not detect Namespace presence.');
        }

        return !count($result);
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
     * @param bool $hasNamespace
     *
     * @return \Generated\Shared\Transfer\ForeignKeyTableTransfer
     */
    protected function processTableXmlElement(SimpleXMLElement $tableXmlElement, bool $hasNamespace): ForeignKeyTableTransfer
    {
        $foreignKeyTableTransfer = new ForeignKeyTableTransfer();
        $foreignKeyTableTransfer->setTableName((string)$tableXmlElement['name']);

        if (!$this->isIndexable($tableXmlElement, $hasNamespace)) {
            return $foreignKeyTableTransfer;
        }

        $foreignKeyColumnNames = $this->getForeignKeyColumnNames($tableXmlElement, $hasNamespace);
        $indexedColumnNames = $this->getIndexedColumnNames($tableXmlElement, $hasNamespace);
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
     * @param bool $hasNamespace
     *
     * @return bool
     */
    protected function isIndexable(SimpleXMLElement $tableXmlElement, bool $hasNamespace): bool
    {
        if ($hasNamespace) {
            $tableXmlElement->registerXPathNamespace('s', 'spryker:schema-01');
        }
        return ($tableXmlElement->xpath($hasNamespace ? 's:behavior[@name="archivable"]' : 'behavior[@name="archivable"]') === false || count($tableXmlElement->xpath($hasNamespace ? 's:behavior[@name="archivable"]' : 'behavior[@name="archivable"]')) === 0);
    }

    /**
     * @param \SimpleXMLElement $tableXmlElement
     * @param bool $hasNamespace
     *
     * @return array
     */
    protected function getForeignKeyColumnNames(SimpleXMLElement $tableXmlElement, bool $hasNamespace): array
    {
        $foreignKeyColumnNames = [];
        if ($hasNamespace) {
            $tableXmlElement->registerXPathNamespace('s', 'spryker:schema-01');
        }
        $foreignKeyReferencesXmlElement = $tableXmlElement->xpath($hasNamespace ? 's:foreign-key/s:reference' : 'foreign-key/reference');

        if ($foreignKeyReferencesXmlElement === false) {
            return $foreignKeyColumnNames;
        }

        foreach ($foreignKeyReferencesXmlElement as $referenceXmlElement) {
            $foreignKeyColumnNames[] = (string)$referenceXmlElement['local'];
        }

        return $foreignKeyColumnNames;
    }

    /**
     * @param \SimpleXMLElement $tableXmlElement
     * @param bool $hasNamespace
     *
     * @return array
     */
    protected function getIndexedColumnNames(SimpleXMLElement $tableXmlElement, bool $hasNamespace): array
    {
        $indexedColumnNames = [];
        if ($hasNamespace) {
            $tableXmlElement->registerXPathNamespace('s', 'spryker:schema-01');
        }
        $indexColumnXmlElements = $tableXmlElement->xpath($hasNamespace ? 's:index/s:index-column | s:column[@primaryKey="true"]' : 'index/index-column | column[@primaryKey="true"]');

        if ($indexColumnXmlElements === false) {
            return $indexedColumnNames;
        }
        foreach ($indexColumnXmlElements as $indexColumnXmlElement) {
            $indexedColumnNames[] = (string)$indexColumnXmlElement['name'];
        }

        return $indexedColumnNames;
    }

    /**
     * @param \SimpleXMLElement $xmlElement
     * @param bool $hasNamespace
     *
     * @return \SimpleXMLElement[]
     */
    protected function getTableXmlElements(SimpleXMLElement $xmlElement, bool $hasNamespace): array
    {
        if ($hasNamespace) {
            $xmlElement->registerXPathNamespace('s', 'spryker:schema-01');
        }
        return $xmlElement->xpath($hasNamespace ? '//s:table' : '//table') ?: [];
    }

    /**
     * @param \SimpleXMLElement $xmlElement
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     * @param bool $hasNamespace
     *
     * @return \Generated\Shared\Transfer\ForeignKeyFileTransfer
     */
    protected function createForeignKeyFileTransfer(SimpleXMLElement $xmlElement, SplFileInfo $fileInfo, bool $hasNamespace): ForeignKeyFileTransfer
    {
        $foreignKeyFileTransfer = new ForeignKeyFileTransfer();

        if ($hasNamespace) {
            $xmlElement->registerXPathNamespace('s', 'spryker:schema-01');
        }

        $database = $xmlElement->xpath($hasNamespace ?  '//s:database' : '//database')[0] ?: [];

        $foreignKeyFileTransfer->setNamespace((string)$database['namespace']);
        $foreignKeyFileTransfer->setPackage((string)$database['package']);
        $foreignKeyFileTransfer->setFilename($fileInfo->getFilename());

        return $foreignKeyFileTransfer;
    }
}
