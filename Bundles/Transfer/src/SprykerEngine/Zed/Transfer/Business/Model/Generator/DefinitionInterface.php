<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

interface DefinitionInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $definition
     *
     * @return $this
     */
    public function setDefinition(array $definition);
}
