<?php
namespace Spryker\Zed\Transfer\Business\Model\Generator;

class DataBuilderDefinition implements DefinitionInterface
{

    protected $name;

    protected $rules = [];

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

    protected function getGeneratorRules(array $properties)
    {
        foreach ($properties as $property) {

            // non arrays and non-basic types are dependencies
            if (preg_match('/^[A-Z]\w+$/', $property['type'])) {
                $this->dependencies[$property['name']];
                continue;
            }

            // basic properties should have 'generate' field for generator
            if (!isset($property['generate'])) {
                continue;
            }
            $this->rules[$property['name']] = $property['generate'];
        }
    }

    private function setName($name)
    {
        $this->name = $name;
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