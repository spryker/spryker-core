<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business\SchemaFinder;

use Spryker\Zed\IndexGenerator\Dependency\Facade\IndexGeneratorToPropelFacadeInterface;
use Symfony\Component\Finder\Finder;

class MergedSchemaFinder implements MergedSchemaFinderInterface
{
    /**
     * @var \Spryker\Zed\IndexGenerator\Dependency\Facade\IndexGeneratorToPropelFacadeInterface
     */
    protected $propelFacade;

    /**
     * @param \Spryker\Zed\IndexGenerator\Dependency\Facade\IndexGeneratorToPropelFacadeInterface $propelFacade
     */
    public function __construct(IndexGeneratorToPropelFacadeInterface $propelFacade)
    {
        $this->propelFacade = $propelFacade;
    }

    /**
     * @return \Symfony\Component\Finder\Finder<\Symfony\Component\Finder\SplFileInfo>
     */
    public function findMergedSchemas(): Finder
    {
        $finder = new Finder();
        $finder->files()->in($this->getSchemaDirectory());

        return $finder;
    }

    /**
     * @return string
     */
    protected function getSchemaDirectory(): string
    {
        return $this->propelFacade->getSchemaDirectory();
    }
}
