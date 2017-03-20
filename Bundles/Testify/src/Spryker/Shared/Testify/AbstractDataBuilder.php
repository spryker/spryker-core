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

    /**
     * @return AbstractTransfer
     */
    abstract protected function getDTO();

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function build($override = [])
    {
        $transfer = $this->getDTO();
        $transfer->fromArray($this->generateFields());
        $transfer->fromArray($this->generateDependencies());
        $transfer->fromArray($override);
        return $transfer;
    }

    public function buildMany($num)
    {
        return array_map([$this, 'build'], array_fill(0, $num, null));
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
        foreach ($this->dependencies as $field => $builder) {
            $data[$field] = $this->locateDataBuilder($builder)->build();
        }
        return $data;
    }

    /**
     * @param $builder
     * @return AbstractDataBuilder
     * @throws \Exception
     */
    protected function locateDataBuilder($builder)
    {
        // can be overridden to locate builders inside a bundle
        $class = get_class($this);
        $classParts = explode('\\', $class);
        array_pop($classParts);
        array_push($classParts, $builder);
        $builderClass = implode('\\', $class);

        if (!class_exists($builderClass)) {
            throw new \Exception("Builder '$builderClass' not found");
        }
        return new $builderClass;
    }

    protected function generateFromRule($rule)
    {
        if (strpos($rule, '=') === 0) {
          return substr($rule, 1);
        }

        $faker = $this->faker;

        return eval("$faker->{$rule}");
    }
}