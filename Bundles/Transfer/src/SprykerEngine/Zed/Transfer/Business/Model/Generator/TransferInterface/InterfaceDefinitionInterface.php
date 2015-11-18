<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\DefinitionInterface;

interface InterfaceDefinitionInterface extends DefinitionInterface
{

    /**
     * @return string
     */
    public function getBundle();

    /**
     * @return string
     */
    public function getContainingBundle();

    /**
     * @return string
     */
    public function getNamespace();

    /**
     * @return array
     */
    public function getUses();

    /**
     * @return array
     */
    public function getMethods();

}
