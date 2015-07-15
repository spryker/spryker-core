<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Internal;

use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;
use SprykerFeature\Zed\Product\Business\Attribute\AttributeManagerInterface;
use Propel\Runtime\Exception\PropelException;

class Install extends AbstractInstaller
{

    /**
     * @var AttributeManagerInterface
     */
    protected $attributeManager;

    /**
     * @param AttributeManagerInterface $attributeManager
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
     */
    public function install()
    {
        $this->createBaseTypes();
    }

    /**
     * @throws PropelException
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
