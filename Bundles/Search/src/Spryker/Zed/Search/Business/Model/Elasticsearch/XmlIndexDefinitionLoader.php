<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Symfony\Component\Finder\Finder;
use Zend\Config\Factory;

class XmlIndexDefinitionLoader
{

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

    public function loadIndexDefinitions()
    {
        $definitions = [];
        $xmlFiles = $this->getXmlFiles();
        foreach ($xmlFiles as $xmlFile) {
            $definitions[] = Factory::fromFile($xmlFile->getPathname(), true)->toArray();
        }

        return $definitions;
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
}
