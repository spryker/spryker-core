<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use RuntimeException;

class DataBuilderDefinition implements DataBuilderDefinitionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $transferName;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $dependencies = [];

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $definition
     *
     * @return $this
     */
    public function setDefinition(array $definition)
    {
        $this->setName($definition['name']);

        if (isset($definition['property'])) {
            $this->getGeneratorRules($definition['property']);
        }

        return $this;
    }

    /**
     * @param array $properties
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function getGeneratorRules(array $properties)
    {
        foreach ($properties as $property) {
            if (!isset($property['type'])) {
                throw new RuntimeException('Invalid databuilder config, missing key `type` for databuilder `' . $this->name . '`');
            }

            // non arrays and non-basic types are dependencies
            if (preg_match('/^[A-Z]\w+(\[\])?$/', $property['type'])) {
                if (isset($property['singular']) && !isset($this->dependencies[$property['singular']])) {
                    $property['name'] = $property['singular'];
                }

                $uppercaseName = ucfirst($property['name']);
                $property['ucfirstName'] = $uppercaseName;
                $property['type'] = str_replace('[]', '', $property['type']); // remove array marker
                $this->dependencies[$uppercaseName] = $property;

                continue;
            }

            // basic properties should have 'dataBuilderRule' field for generator
            if (!isset($property['dataBuilderRule'])) {
                continue;
            }
            $this->rules[] = $property;
        }
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    protected function setName($name)
    {
        $name = ucfirst($name);
        $this->transferName = $name . 'Transfer';

        if (strpos($name, 'Builder') === false) {
            $name .= 'Builder';
        }
        $this->name = ucfirst($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getTransferName()
    {
        return $this->transferName;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }
}
