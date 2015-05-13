<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\DefinitionInterface;

interface ClassDefinitionInterface extends DefinitionInterface
{

    /**
     * @return array
     */
    public function getUses();

    /**
     * @return array
     */
    public function getInterfaces();

    /**
     * @return array
     */
    public function getProperties();

    /**
     * @return array
     */
    public function getConstructorDefinition();

    /**
     * @return array
     */
    public function getMethods();
}
