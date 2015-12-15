<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

interface DefinitionInterface
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $definition
     *
     * @return self
     */
    public function setDefinition(array $definition);

}
