<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

interface GeneratorInterface
{

    /**
     * @param DefinitionInterface $definition
     *
     * @return string
     */
    public function generate(DefinitionInterface $definition);
}
