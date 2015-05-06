<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

interface ClassDefinitionInterface
{
    /**
     * @return string
     */
    public function getName();

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
    public function getMethods();

    /**
     * @return array
     */
    public function getConstructorDefinition();
}
