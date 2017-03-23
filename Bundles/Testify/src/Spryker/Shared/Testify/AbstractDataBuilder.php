<?php
namespace Spryker\Shared\Testify;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

abstract class AbstractDataBuilder
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    protected $defaultRules = [];

    protected $rules = [];

    protected $dependencies = [];

    /**
     * @var AbstractDataBuilder[]
     */
    protected $nestedBuilders = [];

    protected $seedData = [];

    /**
     * @return AbstractTransfer
     */
    abstract protected function getDTO();

    /**
     * @param $builder
     * @return AbstractDataBuilder
     * @throws \Exception
     */
    abstract protected function locateDataBuilder($builder);

    public function __construct($seed = [])
    {
        $this->seedData = $seed;
        $this->rules = $this->defaultRules;
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Removes all rules
     * 
     * @return $this
     */
    public function makeEmpty()
    {
        $this->rules = [];
        return $this;
    }

    /**
     * @return $this
     */
    public function resetData()
    {
        $this->seedData = [];
        return $this;
    }

    /**
     * @param array $seed
     * @return $this
     */
    public function seed(array $seed = [])
    {
        $this->seedData += $seed;
        return $this;
    }

    /**
     * @param $rules array|string
     * @return $this
     * @throws \Exception
     */
    public function with($rules)
    {
        if (!is_array($rules)) {
            $rules = [];
        }
        foreach ($rules as $rule) {
            if (!isset($this->defaultRules[$rule])) {
                throw new \Exception("No rule for $rule is defined");
            }
            $this->rules[$rule] = $this->defaultRules[$rule];
        }
        return $this;
    }

    /**
     * @param $rules array|string
     * @return $this
     */
    public function except($rules)
    {
        if (!is_array($rules)) {
            $rules = [];
        }
        foreach ($rules as $rule) {
            unset($this->rules[$rule]);
        }
        return $this;
    }

    /**
     * @return AbstractTransfer
     */
    public function build()
    {
        $transfer = $this->getDTO();
        $transfer->fromArray($this->generateFields());
        $this->seedData = array_merge($this->getScalarValues($transfer), $this->seedData);

        $this->generateDependencies($transfer);
        $transfer->fromArray($this->seedData, true);

        return $transfer;
    }

    private function getScalarValues(AbstractTransfer $transfer)
    {
        return array_filter($transfer->toArray(false), 'is_scalar');
    }

    protected function generateFields()
    {
        $data = [];
        foreach ($this->rules as $field => $rule) {
          $data[$field] = $this->generateFromRule($rule);
        }
        return $data;
    }

    protected function buildDependency($field, $override = [], $randomize = false)
    {
        if (!isset($this->dependencies[$field])) {
            throw new \Exception("No $field is dependencies list");
        }
        $builder = $this->locateDataBuilder($this->dependencies[$field]);
        $builder->seed($override);
        $this->addDependencyBuilder($field, $builder, $randomize);
    }

    protected function addDependencyBuilder($field, $builder, $randomize)
    {
        $this->nestedBuilders[] = [$field, $builder, $randomize];
    }

    protected function generateDependencies(AbstractTransfer $transfer)
    {
        foreach ($this->nestedBuilders as $builderInfo) {
            list($name, $dependencyBuilder, $randomize) = $builderInfo;

            if (!$randomize) { // add currently generated values
                $dependencyBuilder->seed($this->seedData);
            }
            $nestedTransfer = $dependencyBuilder->build();

            if (!$randomize) { // reuse generated values from nested objects
                $this->seedData = array_merge($this->seedData, $dependencyBuilder->getSeedData());
            }

            if (method_exists($transfer, 'add' . $name)) {
                call_user_func([$transfer, 'add' . $name], $nestedTransfer);
                continue;
            }
            if (method_exists($transfer, 'set' . $name)) {
                call_user_func([$transfer, 'set' . $name], $nestedTransfer);
                continue;
            }
            throw new \Exception("No such dependency: $name");
        }
    }

    protected function generateFromRule($rule)
    {
        if (strpos($rule, '=') === 0) {
          return substr($rule, 1);
        }
        return (string) eval("return \$this->faker->$rule;");
    }

    /**
     * @return array
     */
    public function getSeedData()
    {
        return $this->seedData;
    }

}