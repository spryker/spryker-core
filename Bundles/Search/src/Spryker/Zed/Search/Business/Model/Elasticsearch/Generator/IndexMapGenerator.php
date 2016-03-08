<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Generator;

use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinition;
use Zend\Filter\Word\UnderscoreToCamelCase;

class IndexMapGenerator
{

    const TWIG_TEMPLATES_LOCATION = '/Templates/';

    const CLASS_NAME_SUFFIX = 'IndexMap';

    const CLASS_EXTENSION = '.php';

    const PROPERTY_PATH_SEPARATOR = '.';

    /**
     * @var string
     */
    protected $targetBaseDirectory;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param string $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetBaseDirectory = rtrim($targetDirectory, '/') . '/';

        $loader = new \Twig_Loader_Filesystem(__DIR__ . self::TWIG_TEMPLATES_LOCATION);
        $this->twig = new \Twig_Environment($loader, []);
    }

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinition $indexDefinition
     *
     * @return void
     */
    public function generate(IndexDefinition $indexDefinition)
    {
        $indexNamespaceSuffix = $this->normalizeToClassName($indexDefinition->getIndexName());
        foreach ($indexDefinition->getMappings() as $mappingName => $mapping) {
            $mappingName = $this->normalizeToClassName($mappingName);
            $this->generateIndexMapClass($indexNamespaceSuffix, $mappingName, $mapping);
        }
    }

    /**
     * @param string $mappingName
     *
     * @return string
     */
    protected function normalizeToClassName($mappingName)
    {
        $normalized = preg_replace('/\\W+/', '_', $mappingName);
        $normalized = trim($normalized, '_');

        $filter = new UnderscoreToCamelCase();
        $normalized = $filter->filter($normalized);
        $normalized = ucfirst($normalized);

        return $normalized;
    }

    /**
     * @param string $indexNamespaceSuffix
     * @param string $mappingName
     * @param array $mapping
     *
     * @return void
     */
    protected function generateIndexMapClass($indexNamespaceSuffix, $mappingName, array $mapping)
    {
        $targetDirectory = $this->targetBaseDirectory . $indexNamespaceSuffix . '/';
        $fileName = $mappingName . self::CLASS_NAME_SUFFIX . self::CLASS_EXTENSION;
        $templateData =  $this->getTemplateData($indexNamespaceSuffix, $mappingName, $mapping);
        $fileContent = $this->twig->render('class.php.twig', $templateData);

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        file_put_contents($targetDirectory . $fileName, $fileContent);
    }

    /**
     * @param string $indexNamespaceSuffix
     * @param string $mappingName
     * @param array $mapping
     *
     * @return array
     */
    protected function getTemplateData($indexNamespaceSuffix, $mappingName, array $mapping)
    {
        return [
            'indexNamespaceSuffix' => $indexNamespaceSuffix,
            'className' => $mappingName . self::CLASS_NAME_SUFFIX,
            'constants' => $this->getConstants($mapping),
            'metadata' => $this->getMetadata($mapping),
        ];
    }

    /**
     * @param array $mapping
     * @param string|null $path
     *
     * @return array
     */
    protected function getConstants(array $mapping, $path = null)
    {
        $constants = [];

        foreach ($mapping as $propertyName => $propertyValue) {
            $constants[$this->convertToConstant($path . $propertyName)] = $path . $propertyName;

            if (isset($propertyValue[IndexDefinition::PROPERTIES])) {
                $childMetadata = $this->getConstants(
                    $propertyValue[IndexDefinition::PROPERTIES],
                    $path . $propertyName . self::PROPERTY_PATH_SEPARATOR
                );
                $constants = array_merge($constants, $childMetadata);
            }
        }

        return $constants;
    }

    /**
     * @param array $mapping
     * @param string|null $path
     *
     * @return array
     */
    protected function getMetadata(array $mapping, $path = null)
    {
        $metadata = [];

        foreach ($mapping as $propertyName => $propertyData) {
            $propertyConstantName = $this->convertToConstant($path . $propertyName);
            foreach ($propertyData as $key => $value) {
                if (is_scalar($value)) {
                    $metadata[$propertyConstantName][$key] = $value;
                }
            }

            if (isset($propertyData[IndexDefinition::PROPERTIES])) {
                $childMetadata = $this->getMetadata(
                    $propertyData[IndexDefinition::PROPERTIES],
                    $path . $propertyName . self::PROPERTY_PATH_SEPARATOR
                );
                $metadata = array_merge($metadata, $childMetadata);
            }
        }

        return $metadata;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function convertToConstant($string)
    {
        $normalized = preg_replace('/\\W+/', '_', $string);
        $normalized = trim($normalized, '_');
        $normalized = mb_strtoupper($normalized);

        return $normalized;
    }

}
