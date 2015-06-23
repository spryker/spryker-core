<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\AbstractDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;

class TransferDefinitionBuilder extends AbstractDefinitionBuilder
{

    /**
     * @var TransferDefinitionLoader
     */
    private $loader;

    /**
     * @var TransferDefinitionMerger
     */
    private $merger;

    /**
     * @var ClassDefinition
     */
    private $classDefinition;

    /**
     * @param TransferDefinitionLoader $loader
     * @param TransferDefinitionMerger $merger
     * @param ClassDefinition $classDefinition
     */
    public function __construct(TransferDefinitionLoader $loader, TransferDefinitionMerger $merger, ClassDefinition $classDefinition)
    {
        $this->loader = $loader;
        $this->merger = $merger;
        $this->classDefinition = $classDefinition;
    }

    /**
     * @return ClassDefinition[]
     */
    public function getDefinitions()
    {
        $definitions = $this->loader->getDefinitions();
        $definitions = $this->merger->merge($definitions);;

        return $this->buildDefinitions($definitions, $this->classDefinition);
    }
}
