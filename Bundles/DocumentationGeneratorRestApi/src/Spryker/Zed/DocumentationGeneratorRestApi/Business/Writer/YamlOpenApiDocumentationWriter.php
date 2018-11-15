<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Writer;

use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFilesystemInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToSymfonyYamlAdapter;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToYamlDumperInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig;

class YamlOpenApiDocumentationWriter implements DocumentationWriterInterface
{
    protected const GENERATED_FILE_POSTFIX = '.schema.yml';

    protected const OPENAPI_VERSION = '3.0.0';

    protected const KEY_OPENAPI = 'openapi';
    protected const KEY_INFO = 'info';
    protected const KEY_VERSION = 'version';
    protected const KEY_TITLE = 'title';
    protected const KEY_LICENSE = 'license';
    protected const KEY_NAME = 'name';
    protected const KEY_SERVERS = 'servers';
    protected const KEY_URL = 'url';
    protected const KEY_PATHS = 'paths';
    protected const KEY_COMPONENTS = 'components';
    protected const KEY_SCHEMAS = 'schemas';
    protected const KEY_SECURITY_SCHEMES = 'securitySchemes';

    protected const YAML_NESTING_LEVEL = 9;
    protected const YAML_INDENT = 4;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig
     */
    protected $documentationGeneratorRestApiConfig;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToYamlDumperInterface
     */
    protected $yamlDumper;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFilesystemInterface
     */
    protected $filesystem;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig $documentationGeneratorRestApiConfig
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToYamlDumperInterface $yamlDumper
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFilesystemInterface $filesystem
     */
    public function __construct(
        DocumentationGeneratorRestApiConfig $documentationGeneratorRestApiConfig,
        DocumentationGeneratorRestApiToYamlDumperInterface $yamlDumper,
        DocumentationGeneratorRestApiToFilesystemInterface $filesystem
    ) {
        $this->documentationGeneratorRestApiConfig = $documentationGeneratorRestApiConfig;
        $this->yamlDumper = $yamlDumper;
        $this->filesystem = $filesystem;
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function write(array $data): void
    {
        $dataStructure = $this->getDefaultDataStructure();
        $dataStructure[static::KEY_PATHS] = $data[static::KEY_PATHS];
        $dataStructure[static::KEY_COMPONENTS][static::KEY_SCHEMAS] = $data[static::KEY_SCHEMAS];
        $dataStructure[static::KEY_COMPONENTS][static::KEY_SECURITY_SCHEMES] = $data[static::KEY_SECURITY_SCHEMES];

        $fileName = $this->resolveGeneratedFileName();
        $yaml = $this->yamlDumper->dump(
            $dataStructure,
            static::YAML_NESTING_LEVEL,
            static::YAML_INDENT,
            DocumentationGeneratorRestApiToSymfonyYamlAdapter::DUMP_EMPTY_ARRAY_AS_SEQUENCE
            | DocumentationGeneratorRestApiToSymfonyYamlAdapter::DUMP_MULTI_LINE_LITERAL_BLOCK
        );

        $this->filesystem->dumpFile($fileName, $yaml);
    }

    /**
     * @return array
     */
    protected function getDefaultDataStructure(): array
    {
        return [
            static::KEY_OPENAPI => static::OPENAPI_VERSION,
            static::KEY_INFO => [
                static::KEY_VERSION => $this->documentationGeneratorRestApiConfig->getApiDocumentationVersionInfo(),
                static::KEY_TITLE => $this->documentationGeneratorRestApiConfig->getApiDocumentationTitleInfo(),
                static::KEY_LICENSE => [
                    static::KEY_NAME => $this->documentationGeneratorRestApiConfig->getApiDocumentationLicenceNameInfo(),
                ],
            ],
            static::KEY_SERVERS => [
                [
                    static::KEY_URL => $this->documentationGeneratorRestApiConfig->getRestApplicationDomain(),
                ],
            ],
            static::KEY_PATHS => [],
            static::KEY_COMPONENTS => [
                static::KEY_SECURITY_SCHEMES => [],
                static::KEY_SCHEMAS => [],
            ],
        ];
    }

    /**
     * @return string
     */
    protected function resolveGeneratedFileName(): string
    {
        return $this->documentationGeneratorRestApiConfig->getGeneratedFileOutputDirectory()
            . DIRECTORY_SEPARATOR
            . $this->documentationGeneratorRestApiConfig->getGeneratedFilePrefix()
            . static::GENERATED_FILE_POSTFIX;
    }
}
