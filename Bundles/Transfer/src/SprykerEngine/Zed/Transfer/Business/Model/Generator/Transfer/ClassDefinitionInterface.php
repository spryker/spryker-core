<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator\Transfer;

use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionInterface;

interface ClassDefinitionInterface extends DefinitionInterface
{

    /**
     * @return array
     */
    public function getUses();

    /**
     * @return array
     */
    public function getBundles();

    /**
     * @return array
     */
    public function getConstants();

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

    /**
     * @return array
     */
    public function getNormalizedProperties();

}
