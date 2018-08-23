<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Writer;

use Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\FileNotCreatedException;
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

    protected const YAML_NESTING_LEVEL = 9;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig
     */
    protected $restApiDocumentationGeneratorConfig;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToYamlDumperInterface
     */
    protected $yamlDumper;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig $restApiDocumentationGeneratorConfig
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToYamlDumperInterface $yamlDumper
     */
    public function __construct(
        RestApiDocumentationGeneratorConfig $restApiDocumentationGeneratorConfig,
        RestApiDocumentationGeneratorToYamlDumperInterface $yamlDumper
    ) {
        $this->restApiDocumentationGeneratorConfig = $restApiDocumentationGeneratorConfig;
        $this->yamlDumper = $yamlDumper;
    }

    /**
     * @param array $paths
     * @param array $schemas
     *
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\FileNotCreatedException
     *
     * @return void
     */
    public function write(array $paths, array $schemas): void
    {
        $data = $this->getDefaultDataStructure();
        $data[static::KEY_PATHS] = $paths;
        $data[static::KEY_COMPONENTS][static::KEY_SCHEMAS] = $schemas;

        $bytesWritten = file_put_contents(
            $this->resolveGeneratedFileName(),
            $this->yamlDumper->dump($data, static::YAML_NESTING_LEVEL)
        );
        if (!$bytesWritten) {
            throw new FileNotCreatedException('Unable to create file, please check permissions and free space available on device.');
        }
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
                static::KEY_SCHEMAS => [],
            ],
        ];
    }

    /**
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\FileNotCreatedException
     *
     * @return string
     */
    protected function resolveGeneratedFileName(): string
    {
        $targetDirectory = $this->restApiDocumentationGeneratorConfig->getTargetDirectory();
        if (substr($targetDirectory, -1) !== DIRECTORY_SEPARATOR) {
            $targetDirectory .= '/';
        }

        if (!is_dir($targetDirectory) && !mkdir($targetDirectory, static::TARGET_DIRECTORY_PERMISSIONS, true) && !is_dir($targetDirectory)) {
            throw new FileNotCreatedException('Unable to create directory.');
        }

        $fileName = $this->restApiDocumentationGeneratorConfig->getGeneratedFileName();

        if (substr_compare($fileName, static::GENERATED_FILE_POSTFIX, -strlen(static::GENERATED_FILE_POSTFIX)) === 0) {
            return $fileName;
        }

        return $targetDirectory . $fileName . static::GENERATED_FILE_POSTFIX;
    }
}
