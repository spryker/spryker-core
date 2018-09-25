<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Writer;

use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFilesystemInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToSymfonyYamlAdapter;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToYamlDumperInterface;
use Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig;

class YamlRestApiDocumentationWriter implements RestApiDocumentationWriterInterface
{
    protected const GENERATED_FILE_POSTFIX = '.schema.yml';
    protected const TARGET_DIRECTORY_PERMISSIONS = 0775;

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
     * @var \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig
     */
    protected $restApiDocumentationGeneratorConfig;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToYamlDumperInterface
     */
    protected $yamlDumper;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFilesystemInterface
     */
    protected $filesystem;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig $restApiDocumentationGeneratorConfig
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToYamlDumperInterface $yamlDumper
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFilesystemInterface $filesystem
     */
    public function __construct(
        RestApiDocumentationGeneratorConfig $restApiDocumentationGeneratorConfig,
        RestApiDocumentationGeneratorToYamlDumperInterface $yamlDumper,
        RestApiDocumentationGeneratorToFilesystemInterface $filesystem
    ) {
        $this->restApiDocumentationGeneratorConfig = $restApiDocumentationGeneratorConfig;
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
            RestApiDocumentationGeneratorToSymfonyYamlAdapter::DUMP_EMPTY_ARRAY_AS_SEQUENCE
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
                static::KEY_VERSION => $this->restApiDocumentationGeneratorConfig->getInfoApiVersion(),
                static::KEY_TITLE => $this->restApiDocumentationGeneratorConfig->getInfoApiTitle(),
                static::KEY_LICENSE => [
                    static::KEY_NAME => $this->restApiDocumentationGeneratorConfig->getInfoApiInfoLicenceName(),
                ],
            ],
            static::KEY_SERVERS => [
                [
                    static::KEY_URL => $this->restApiDocumentationGeneratorConfig->getRestApplicationDomain(),
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
        return $this->restApiDocumentationGeneratorConfig->getTargetDirectory()
            . DIRECTORY_SEPARATOR
            . $this->restApiDocumentationGeneratorConfig->getGeneratedFileName()
            . static::GENERATED_FILE_POSTFIX;
    }
}
