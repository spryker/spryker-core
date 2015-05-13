<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

interface DefinitionInterface
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
    public function getMethods();
}
