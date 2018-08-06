<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business\IndexGenerator;

use DOMDocument;
use Generated\Shared\Transfer\ForeignKeyFileTransfer;
use Spryker\Zed\IndexGenerator\Business\ForeignKeysProvider\ForeignKeysProviderInterface;
use Spryker\Zed\IndexGenerator\IndexGeneratorConfig;
use Symfony\Component\Finder\Finder;

class PostgresPostgresIndexGenerator implements PostgresIndexGeneratorInterface
{
    const POSTGRES_INDEX_NAME_MAX_LENGTH = 63;

    /**
     * @var \Spryker\Zed\IndexGenerator\IndexGeneratorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\IndexGenerator\Business\ForeignKeysProvider\ForeignKeysProviderInterface
     */
    protected $foreignKeysProvider;

    /**
     * @param \Spryker\Zed\IndexGenerator\IndexGeneratorConfig $config
     * @param \Spryker\Zed\IndexGenerator\Business\ForeignKeysProvider\ForeignKeysProviderInterface $foreignKeysProvider
     */
    public function __construct(IndexGeneratorConfig $config, ForeignKeysProviderInterface $foreignKeysProvider)
    {
        $this->config = $config;
        $this->foreignKeysProvider = $foreignKeysProvider;
    }

    /**
     * @return void
     */
    public function generateIndexes(): void
    {
        $this->deleteOldSchemaFiles();
        $this->generateSchemaFiles();
    }

    /**
     * @return void
     */
    protected function deleteOldSchemaFiles(): void
    {
        foreach ($this->getSchemaFinder() as $schemaFileInfo) {
            unlink($schemaFileInfo->getPathname());
        }
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getSchemaFinder(): Finder
    {
        $targetDirectory = $this->getTargetDirectory();
        $finder = new Finder();

        return $finder->in($targetDirectory)->files();
    }

    /**
     * @return void
     */
    protected function generateSchemaFiles(): void
    {
        foreach ($this->getForeignKeyList() as $foreignKeyFileTransfer) {
            $this->generateSchemaFile($foreignKeyFileTransfer);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\ForeignKeyFileTransfer[]
     */
    protected function getForeignKeyList(): array
    {
        return $this->foreignKeysProvider->getForeignKeyList();
    }

    /**
     * @param \Generated\Shared\Transfer\ForeignKeyFileTransfer $foreignKeyFileTransfer
     *
     * @return void
     */
    protected function generateSchemaFile(ForeignKeyFileTransfer $foreignKeyFileTransfer): void
    {
        $domDocument = $this->createDomDocument($foreignKeyFileTransfer);

        foreach ($foreignKeyFileTransfer->getForeignKeyTables() as $foreignKeyTable) {
            $tableElement = $domDocument->createElement('table');
            $tableElement->setAttribute('name', $foreignKeyTable->getTableName());
            $domDocument->documentElement->appendChild($tableElement);

            foreach ($foreignKeyTable->getColumns() as $column) {
                $indexName = $this->getIndexName($foreignKeyTable->getTableName(), $column);

                $indexElement = $domDocument->createElement('index');
                $tableElement->appendChild($indexElement);

                $indexColumnElement = $domDocument->createElement('index-column');
                $indexColumnElement->setAttribute('name', $column);

                $indexElement->setAttribute('name', $indexName);
                $indexElement->appendChild($indexColumnElement);
            }
        }

        if (!$this->isDocumentEmpty($domDocument)) {
            $this->saveDocument($domDocument, $foreignKeyFileTransfer->getFilename());
        }
    }

    /**
     * @param \DOMDocument $document
     * @param string $fileName
     *
     * @return int
     */
    protected function saveDocument(DOMDocument $document, string $fileName): int
    {
        return $document->save($this->getTargetDirectory() . DIRECTORY_SEPARATOR . $fileName);
    }

    /**
     * @param \DOMDocument $document
     *
     * @return bool
     */
    protected function isDocumentEmpty(DOMDocument $document): bool
    {
        return (!$document->documentElement->hasChildNodes());
    }

    /**
     * @param \Generated\Shared\Transfer\ForeignKeyFileTransfer $foreignKeyFile
     *
     * @return \DOMDocument
     */
    protected function createDomDocument(ForeignKeyFileTransfer $foreignKeyFile): DOMDocument
    {
        $baseXml = sprintf($this->getFileHeader(), $foreignKeyFile->getNamespace(), $foreignKeyFile->getPackage());

        $domDocument = new DOMDocument();
        $domDocument->formatOutput = true;
        $domDocument->loadXML($baseXml);

        return $domDocument;
    }

    /**
     * @param string $tableName
     * @param string $indexColumnName
     *
     * @return string
     */
    protected function getIndexName(string $tableName, string $indexColumnName): string
    {
        $indexName = sprintf('index-%s-%s', $tableName, $indexColumnName);

        if ($this->isLongerThanIndexNameMaxLength($indexName)) {
            $hash = substr(md5($indexName), 0, 12);
            $indexName = substr($indexName, 0, 50) . '-' . $hash;
        }

        return $indexName;
    }

    /**
     * @param string $indexName
     *
     * @return bool
     */
    protected function isLongerThanIndexNameMaxLength(string $indexName): bool
    {
        return (mb_strlen($indexName) > static::POSTGRES_INDEX_NAME_MAX_LENGTH);
    }

    /**
     * @return string
     */
    protected function getTargetDirectory(): string
    {
        $targetDirectory = $this->config->getTargetDirectory();
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, $this->config->getPermissionMode(), true);
        }

        return $targetDirectory;
    }

    /**
     * @return string
     */
    protected function getFileHeader(): string
    {
        return '<database xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
          name="zed"
          xsi:noNamespaceSchemaLocation="http://static.spryker.com/schema-01.xsd"
          namespace="%s"
          package="%s"/>';
    }
}
