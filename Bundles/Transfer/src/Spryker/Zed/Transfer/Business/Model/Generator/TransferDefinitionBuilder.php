<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

class TransferDefinitionBuilder extends AbstractDefinitionBuilder
{
    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\LoaderInterface
     */
    protected $loader;

    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\MergerInterface
     */
    protected $merger;

    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinitionInterface
     */
    protected $classDefinition;

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\LoaderInterface $loader
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\MergerInterface $merger
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinitionInterface $classDefinition
     */
    public function __construct(LoaderInterface $loader, MergerInterface $merger, ClassDefinitionInterface $classDefinition)
    {
        $this->loader = $loader;
        $this->merger = $merger;
        $this->classDefinition = $classDefinition;
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinitionInterface[]|\Spryker\Zed\Transfer\Business\Model\Generator\DefinitionInterface[]
     */
    public function getDefinitions()
    {
        $definitions = $this->loader->getDefinitions();
        $definitions = $this->merger->merge($definitions);

        return $this->buildDefinitions($definitions, $this->classDefinition);
    }
}
