<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Spryker\Zed\Transfer\Business\Model\Generator\AbstractDefinitionBuilder;
use Spryker\Zed\Transfer\Business\Model\Generator\LoaderInterface;
use Spryker\Zed\Transfer\Business\Model\Generator\MergerInterface;

class DataBuilderDefinitionBuilder extends AbstractDefinitionBuilder
{

    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\LoaderInterface
     */
    private $loader;

    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\MergerInterface
     */
    private $merger;

    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\DataBuilderDefinition
     */
    private $dataBuilderDefinition;

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\LoaderInterface $loader
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\MergerInterface $merger
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\DataBuilderDefinition|\Spryker\Zed\Transfer\Business\Model\Generator\DataBuilderDefinitionInterface $dataBuilderDefinition
     */
    public function __construct(LoaderInterface $loader, MergerInterface $merger, DataBuilderDefinitionInterface $dataBuilderDefinition)
    {
        $this->loader = $loader;
        $this->merger = $merger;
        $this->dataBuilderDefinition = $dataBuilderDefinition;
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinitionInterface[]
     */
    public function getDefinitions()
    {
        $definitions = $this->loader->getDefinitions();
        $definitions = $this->merger->merge($definitions);

        return $this->buildDefinitions($definitions, $this->dataBuilderDefinition);
    }

}
