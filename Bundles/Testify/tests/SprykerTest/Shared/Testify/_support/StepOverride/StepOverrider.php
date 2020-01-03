<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\StepOverride;

use Codeception\Scenario;
use Codeception\Step;
use Codeception\Test\Metadata;
use ReflectionClass;
use ReflectionObject;

class StepOverrider extends Scenario
{
    /**
     * @var \Codeception\Scenario
     */
    protected $scenario;

    /**
     * @var string
     */
    protected $stepDescription;

    /**
     * @var callable
     */
    protected $releaseHook;

    /**
     * @var string[]
     */
    protected $prepositions = [];

    /**
     * @param \Codeception\Scenario $scenario
     * @param string $stepDescription
     * @param callable $releaseHook
     */
    public function __construct(Scenario $scenario, string $stepDescription, callable $releaseHook)
    {
        $this->scenario = $scenario;
        $this->stepDescription = $stepDescription;
        $this->releaseHook = $releaseHook;

        // TODO [E2E] Consider other way to override steps
        $scenarioReflection = new ReflectionObject($scenario);
        $testProperty = $scenarioReflection->getProperty('test');
        $testProperty->setAccessible(true);
        parent::__construct($testProperty->getValue($scenario));
    }

    /**
     * @see \Codeception\Scenario::addStep()
     *
     * @param \Codeception\Step $step
     *
     * @return mixed
     */
    public function runStep(Step $step)
    {
        $className = __NAMESPACE__ . '\\' . basename((new ReflectionClass($step))->getShortName()) . 'Extender';

        if (class_exists($className)) {
            $step = new $className($step->getAction(), $step->getArguments());
        }

        if ($step instanceof StepDescriptionExtender) {
            $step = $step->setStepDescription($this->stepDescription . $this->getPreposition());
        }

        ($this->releaseHook)();

        return $this->scenario->runStep($step);
    }

    /**
     * @return string
     */
    protected function getPreposition(): string
    {
        return count($this->prepositions) > 0 ? ' ' . trim(implode(' ', $this->prepositions)) . ' ' : '';
    }

    /**
     * @param string $preposition
     *
     * @return static
     */
    public function addPreposition(string $preposition): self
    {
        $this->prepositions[] = trim($preposition);

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * @inheritDoc
     */
    public function getMetadata()
    {
        return new Metadata();
    }

    /**
     * @inheritDoc
     */
    public function setFeature($feature)
    {
        $this->scenario->setFeature($feature);
    }

    /**
     * @inheritDoc
     */
    public function getFeature()
    {
        return $this->scenario->getFeature();
    }

    /**
     * @inheritDoc
     */
    public function getGroups()
    {
        return $this->scenario->getGroups();
    }

    /**
     * @inheritDoc
     */
    public function current($key)
    {
        return $this->scenario->current($key);
    }

    /**
     * @inheritDoc
     */
    public function addStep(Step $step)
    {
        $this->scenario->addStep($step);
    }

    /**
     * @inheritDoc
     */
    public function getSteps()
    {
        return $this->scenario->getSteps();
    }

    /**
     * @inheritDoc
     */
    public function getHtml()
    {
        return $this->scenario->getHtml();
    }

    /**
     * @inheritDoc
     */
    public function getText()
    {
        return $this->scenario->getText();
    }

    /**
     * @inheritDoc
     */
    public function comment($comment)
    {
        $this->scenario->comment($comment);
    }

    /**
     * @inheritDoc
     */
    public function skip($message = '')
    {
        $this->scenario->skip($message);
    }

    /**
     * @inheritDoc
     */
    public function incomplete($message = '')
    {
        $this->scenario->incomplete($message);
    }

    /**
     * @inheritDoc
     */
    public function setMetaStep($metaStep)
    {
        $this->scenario->setMetaStep($metaStep);
    }

    /**
     * @inheritDoc
     */
    public function getMetaStep()
    {
        return $this->scenario->getMetaStep();
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->scenario, $name], $arguments);
    }
}
