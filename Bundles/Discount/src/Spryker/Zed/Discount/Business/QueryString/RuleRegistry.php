<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\QueryString;

class RuleRegistry
{
    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\RuleInterface[]
     */
    protected $rules = [];

    /**
     * @param array|\Spryker\Zed\Discount\Business\QueryString\RuleInterface[] $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = array_change_key_case($rules, CASE_LOWER);
    }

    /**
     * @param string $name
     * @return \Spryker\Zed\Discount\Business\QueryString\RuleInterface|null
     * @throws \Exception
     */
    public function getByName($name)
    {
        if (!isset($this->rules[$name])) {
            //@todo log about missing rule
            return null;
        }

        return $this->rules[$name];
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\FalseRule
     */
    protected function createFalseRule()
    {
        return new FalseRule();
    }
}
