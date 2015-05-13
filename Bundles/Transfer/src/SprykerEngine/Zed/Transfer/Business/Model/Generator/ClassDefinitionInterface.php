<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

interface ClassDefinitionInterface extends DefinitionInterface
{

    /**
     * @return array
     */
    public function getProperties();

    /**
     * @return array
     */
    public function getConstructorDefinition();

}
