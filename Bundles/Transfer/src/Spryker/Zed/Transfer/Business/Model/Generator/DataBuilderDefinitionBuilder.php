<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Psr\Log\LoggerInterface;

class DataBuilderDefinitionBuilder extends AbstractDefinitionBuilder
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
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\DataBuilderDefinitionInterface
     */
    protected $dataBuilderDefinition;

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\LoaderInterface $loader
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\MergerInterface $merger
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\DataBuilderDefinitionInterface $dataBuilderDefinition
     */
    public function __construct(LoaderInterface $loader, MergerInterface $merger, DataBuilderDefinitionInterface $dataBuilderDefinition)
    {
        $this->loader = $loader;
        $this->merger = $merger;
        $this->dataBuilderDefinition = $dataBuilderDefinition;
    }

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return array<\Spryker\Zed\Transfer\Business\Model\Generator\DefinitionInterface>
     */
    public function getDefinitions(LoggerInterface $messenger): array
    {
        $definitions = $this->loader->getDefinitions();
        $definitions = $this->merger->merge($definitions);

        return $this->buildDefinitions($definitions, $this->dataBuilderDefinition);
    }
}
