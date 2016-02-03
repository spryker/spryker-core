<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

interface GeneratorInterface
{

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionInterface $definition
     *
     * @return string
     */
    public function generate(DefinitionInterface $definition);

}
