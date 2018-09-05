<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Dependency\External;

use OpenApi\Analysis;
use OpenApi\StaticAnalyser;

class RestApiDocumentationGeneratorToOpenApiAnnotationsAnalyserAdapter implements RestApiDocumentationGeneratorToAnnotationsAnalyserInterface
{
    /**
     * @var \OpenApi\StaticAnalyser
     */
    protected $analyser;

    /**
     * @var \OpenApi\Analysis
     */
    protected $analysis;

    public function __construct()
    {
        $this->analyser = new StaticAnalyser();
        $this->analysis = new Analysis();
    }

    /**
     * @param string $filename
     *
     * @return void
     */
    public function analyse($filename): void
    {
        $this->analysis->addAnalysis($this->analyser->fromFile($filename));
    }

    /**
     * @return void
     */
    public function process(): void
    {
        $this->analysis->process();
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        foreach ($this->analysis->openapi->paths as $path) {
            if (!$path->validate()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        $paths = [];

        foreach ($this->analysis->openapi->paths as $path) {
            $generatedPath = json_decode($path->toJson(0), true);
            unset($generatedPath['path']);
            $paths[$path->path] = $generatedPath;
        }

        return $paths;
    }
}
