<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\IndexMap\Generator;

use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;
use Twig\Environment;
use Zend\Filter\Word\UnderscoreToCamelCase;

class IndexMapGenerator implements IndexMapGeneratorInterface
{
    public const CLASS_NAME_SUFFIX = 'IndexMap';
    public const CLASS_EXTENSION = '.php';
    public const PROPERTIES = 'properties';
    public const PROPERTY_PATH_SEPARATOR = '.';
    public const TEMPLATE_VARIABLE_CLASS_NAME = 'className';
    public const TEMPLATE_VARIABLE_CONSTANTS = 'constants';
    public const TEMPLATE_VARIABLE_METADATA = 'metadata';

    /**
     * @var \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected $config;

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig $config
     * @param \Twig\Environment $twig
     */
    public function __construct(SearchElasticsearchConfig $config, Environment $twig)
    {
        $this->config = $config;
        $this->twig = $twig;
    }

    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionTransfer $indexDefinition
     *
     * @return void
     */
    public function generate(IndexDefinitionTransfer $indexDefinition): void
    {
        foreach ($indexDefinition->getMappings() as $mappingName => $mapping) {
            $mappingName = $this->normalizeToClassName($mappingName);
            $this->generateIndexMapClass($mappingName, $mapping);
        }
    }

    /**
     * @param string $mappingName
     *
     * @return string
     */
    protected function normalizeToClassName(string $mappingName): string
    {
        $normalized = preg_replace('/\\W+/', '_', $mappingName);
        $normalized = trim($normalized, '_');

        $filter = new UnderscoreToCamelCase();
        $normalized = $filter->filter($normalized);
        $normalized = ucfirst($normalized);

        return $normalized;
    }

    /**
     * @param string $mappingName
     * @param array $mapping
     *
     * @return void
     */
    protected function generateIndexMapClass(string $mappingName, array $mapping): void
    {
        $targetDirectory = $this->config->getClassTargetDirectory();
        $fileName = $mappingName . self::CLASS_NAME_SUFFIX . self::CLASS_EXTENSION;
        $templateData = $this->getTemplateData($mappingName, $mapping);
        $fileContent = $this->twig->render('class.php.twig', $templateData);

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, $this->config->getPermissionMode(), true);
        }

        file_put_contents($targetDirectory . $fileName, $fileContent);
    }

    /**
     * @param string $mappingName
     * @param array $mapping
     *
     * @return array
     */
    protected function getTemplateData(string $mappingName, array $mapping): array
    {
        $properties = $this->getMappingProperties($mapping);

        return [
            static::TEMPLATE_VARIABLE_CLASS_NAME => $mappingName . static::CLASS_NAME_SUFFIX,
            static::TEMPLATE_VARIABLE_CONSTANTS => $this->getConstants($properties),
            static::TEMPLATE_VARIABLE_METADATA => $this->getMetadata($properties),
        ];
    }

    /**
     * @param array $properties
     * @param string|null $path
     *
     * @return array
     */
    protected function getConstants(array $properties, ?string $path = null): array
    {
        $constants = [];

        foreach ($properties as $propertyName => $propertyData) {
            $propertyConstantName = $this->convertToConstant($path . $propertyName);

            $constants[$propertyConstantName] = $path . $propertyName;

            $constants = $this->getChildConstants($path, $propertyData, $propertyName, $constants);
        }

        return $constants;
    }

    /**
     * @param array $properties
     * @param string|null $path
     *
     * @return array
     */
    protected function getMetadata(array $properties, ?string $path = null): array
    {
        $metadata = [];

        foreach ($properties as $propertyName => $propertyData) {
            $propertyConstantName = $this->convertToConstant($path . $propertyName);

            $metadata = $this->getScalarMetadata($propertyData, $metadata, $propertyConstantName);

            $metadata = $this->getChildMetadata($path, $propertyData, $propertyName, $metadata);
        }

        return $metadata;
    }

    /**
     * @param array $mapping
     *
     * @return array
     */
    protected function getMappingProperties(array $mapping): array
    {
        return isset($mapping[static::PROPERTIES]) ? $mapping[static::PROPERTIES] : [];
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function convertToConstant(string $string): string
    {
        $normalized = preg_replace('/\\W+/', '_', $string);
        $normalized = trim($normalized, '_');
        $normalized = mb_strtoupper($normalized);

        return $normalized;
    }

    /**
     * @param array $propertyData
     * @param array $metadata
     * @param string $propertyConstantName
     *
     * @return array
     */
    protected function getScalarMetadata(array $propertyData, array $metadata, string $propertyConstantName): array
    {
        foreach ($propertyData as $key => $value) {
            if (is_scalar($value)) {
                $metadata[$propertyConstantName][$key] = $value;
            }
        }

        return $metadata;
    }

    /**
     * @param string|null $path
     * @param array $propertyData
     * @param string $propertyName
     * @param array $metadata
     *
     * @return array
     */
    protected function getChildMetadata(?string $path, array $propertyData, string $propertyName, array $metadata): array
    {
        if (!isset($propertyData[static::PROPERTIES])) {
            return $metadata;
        }

        $path .= $propertyName . static::PROPERTY_PATH_SEPARATOR;

        $childMetadata = $this->getMetadata($propertyData[static::PROPERTIES], $path);

        $metadata = array_merge($metadata, $childMetadata);

        return $metadata;
    }

    /**
     * @param string|null $path
     * @param array $propertyData
     * @param string $propertyName
     * @param array $constants
     *
     * @return array
     */
    protected function getChildConstants(?string $path, array $propertyData, string $propertyName, array $constants): array
    {
        if (!isset($propertyData[static::PROPERTIES])) {
            return $constants;
        }

        $path .= $propertyName . static::PROPERTY_PATH_SEPARATOR;

        $childMetadata = $this->getConstants($propertyData[static::PROPERTIES], $path);

        $constants = array_merge($constants, $childMetadata);

        return $constants;
    }
}
