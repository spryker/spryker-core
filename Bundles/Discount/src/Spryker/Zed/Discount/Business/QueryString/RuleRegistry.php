<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
