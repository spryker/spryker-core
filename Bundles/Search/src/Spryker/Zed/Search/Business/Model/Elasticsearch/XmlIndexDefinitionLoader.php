<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Symfony\Component\Finder\Finder;
use Zend\Config\Config;
use Zend\Config\Factory;
use Zend\Filter\Word\CamelCaseToUnderscore;

class XmlIndexDefinitionLoader implements IndexDefinitionLoaderInterface
{
    const INDEX = 'index';

    /**
     * @var array
     */
    protected $sourceDirectories;

    /**
     * @param array $sourceDirectories
     */
    public function __construct(array $sourceDirectories)
    {
        $this->sourceDirectories = $sourceDirectories;
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\IndexDefinition[]
     */
    public function loadIndexDefinitions()
    {
        $indexDefinitions = [];
        $xmlFiles = $this->getXmlFiles();
        foreach ($xmlFiles as $xmlFile) {
            $definitionConfig = Factory::fromFile($xmlFile->getPathname());

            $indexDefinitions = array_merge($indexDefinitions, $this->createIndexDefinitions($definitionConfig));
        }

        return $indexDefinitions;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getXmlFiles()
    {
        $finder = new Finder();
        $finder->in($this->sourceDirectories)->name('*.xml');

        return $finder;
    }

    /**
     * @param array $definitionConfig
     *
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\IndexDefinition[]
     */
    protected function createIndexDefinitions(array $definitionConfig)
    {
        $indexDefinitionData = $definitionConfig[self::INDEX];
        if ($this->isAssociativeArray($indexDefinitionData)) {
            return [$this->createIndexDefinition($indexDefinitionData)];
        }

        $definitions = [];
        foreach ($indexDefinitionData as $data) {
            $definitions[] = $this->createIndexDefinition($data);
        }

        return $definitions;
    }

    /**
     * @param array $definitionData
     *
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\IndexDefinition
     */
    protected function createIndexDefinition(array $definitionData)
    {
        return new IndexDefinition($definitionData);
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    protected function isAssociativeArray(array $array)
    {
        return array_values($array) !== $array;
    }

}
