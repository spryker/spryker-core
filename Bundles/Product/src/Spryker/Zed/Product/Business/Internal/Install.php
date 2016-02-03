<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Internal;

use Spryker\Zed\Installer\Business\Model\AbstractInstaller;
use Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface;

class Install extends AbstractInstaller
{

    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface
     */
    protected $attributeManager;

    /**
     * @param \Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface $attributeManager
     */
    public function __construct(AttributeManagerInterface $attributeManager)
    {
        $this->attributeManager = $attributeManager;
    }

    /**
     * type => input representation
     * e.g.: string => input
     *
     * @return array
     */
    protected function getBaseTypes()
    {
        return [
            'string' => 'input',
            'integer' => 'number',
            'float' => 'input',
            'boolean' => 'checkbox',
            'enum' => 'select',
            'list' => 'multi',
        ];
    }

    /**
     * @return void
     */
    public function install()
    {
        $this->createBaseTypes();
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function createBaseTypes()
    {
        foreach ($this->getBaseTypes() as $name => $inputType) {
            if (!$this->attributeManager->hasAttributeType($name)) {
                $this->attributeManager->createAttributeType($name, $inputType);
            }
        }
    }

}
