<?php
namespace Spryker\Shared\Testify;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

abstract class AbstractDataBuilder
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    protected $rules = [];

    protected $dependencies = [];

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

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @param array $override
     * @param bool $randomize
     * @return AbstractTransfer
     */
    public function build($override = [], $randomize = true)
    {
        if ($randomize) {
            $this->seedData = $override;
        }
        $transfer = $this->getDTO();
        $transfer->fromArray($this->generateFields());
        $this->seedData = $this->getScalarValues($transfer);
        
        $transfer->fromArray($this->generateDependencies());
        $transfer->fromArray($override, true);
        return $transfer;
    }

    private function getScalarValues(AbstractTransfer $dto)
    {
        return array_filter($dto->toArray(false), 'is_scalar');
    }


    protected function generateFields()
    {
        $data = [];
        foreach ($this->rules as $field => $rule) {
          $data[$field] = $this->generateFromRule($rule);
        }
        return $data;
    }

    public function generateDependencies()
    {
        $data = [];
        foreach ($this->dependencies as $field => $builderName) {
            $builder = $this->locateDataBuilder($builderName);
            $data[$field] = $builder->build($this->seedData);
            $this->seedData += $builder->getSeedData();
        }
        return $data;
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