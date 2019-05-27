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
     * @inheritdoc
     */
    public function getMetadata()
    {
        return new Metadata();
    }

    /**
     * @inheritdoc
     */
    public function setFeature($feature)
    {
        $this->scenario->setFeature($feature);
    }

    /**
     * @inheritdoc
     */
    public function getFeature()
    {
        return $this->scenario->getFeature();
    }

    /**
     * @inheritdoc
     */
    public function getGroups()
    {
        return $this->scenario->getGroups();
    }

    /**
     * @inheritdoc
     */
    public function current($key)
    {
        return $this->scenario->current($key);
    }

    /**
     * @inheritdoc
     */
    public function addStep(Step $step)
    {
        $this->scenario->addStep($step);
    }

    /**
     * @inheritdoc
     */
    public function getSteps()
    {
        return $this->scenario->getSteps();
    }

    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        return $this->scenario->getHtml();
    }

    /**
     * @inheritdoc
     */
    public function getText()
    {
        return $this->scenario->getText();
    }

    /**
     * @inheritdoc
     */
    public function comment($comment)
    {
        $this->scenario->comment($comment);
    }

    /**
     * @inheritdoc
     */
    public function skip($message = '')
    {
        $this->scenario->skip($message);
    }

    /**
     * @inheritdoc
     */
    public function incomplete($message = '')
    {
        $this->scenario->incomplete($message);
    }

    /**
     * @inheritdoc
     */
    public function setMetaStep($metaStep)
    {
        $this->scenario->setMetaStep($metaStep);
    }

    /**
     * @inheritdoc
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
