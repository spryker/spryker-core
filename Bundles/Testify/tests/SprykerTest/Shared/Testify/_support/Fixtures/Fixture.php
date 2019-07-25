<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Fixtures;

use Codeception\Actor;
use Codeception\Exception\TestRuntimeException;
use Codeception\Lib\Console\Message;
use Codeception\Lib\Parser;
use Codeception\Test\Feature\ScenarioLoader;
use Codeception\Test\Interfaces\ScenarioDriven;
use Codeception\Test\Metadata;
use Codeception\Test\Test;
use Codeception\Util\Annotation;
use Codeception\Util\ReflectionHelper;
use ReflectionMethod;

class Fixture extends Test implements ScenarioDriven
{
    use ScenarioLoader;

    /**
     * @var \Codeception\Lib\Parser
     */
    protected $parser;

    /**
     * @var object
     */
    protected $testClassInstance;

    /**
     * @var string
     */
    protected $testMethod;

    /**
     * @param object $testClass
     * @param string $methodName
     * @param string $fileName
     */
    public function __construct($testClass, string $methodName, string $fileName)
    {
        $metadata = new Metadata();
        $metadata->setName($methodName);
        $metadata->setFilename($fileName);
        $this->setMetadata($metadata);
        $this->testClassInstance = $testClass;
        $this->testMethod = $methodName;
        $this->createScenario();
        $this->parser = new Parser($this->getScenario(), $this->getMetadata());
    }

    /**
     * @return void
     */
    public function preload()
    {
        $this->scenario->setFeature($this->getSpecificationFromMethod());
        $code = $this->getSourceCode();
        $this->parser->parseFeature($code);
        $this->getMetadata()->setParamsFromAnnotations(
            Annotation::forMethod(
                $this->testClassInstance,
                $this->testMethod
            )->raw()
        );
        $this->getMetadata()->getService('di')->injectDependencies($this->testClassInstance);
    }

    /**
     * @return string
     */
    public function getSourceCode()
    {
        $method = new ReflectionMethod($this->testClassInstance, $this->testMethod);
        $start_line = $method->getStartLine() - 1; // it's actually - 1, otherwise you wont get the function() block
        $end_line = $method->getEndLine();
        $source = file($method->getFileName());

        return implode('', array_slice($source, $start_line, $end_line - $start_line));
    }

    /**
     * @return string
     */
    public function getSpecificationFromMethod(): string
    {
        $text = $this->testMethod;
        $text = preg_replace('/([A-Z]+)([A-Z][a-z])/', '\\1 \\2', $text);
        $text = preg_replace('/([a-z\d])([A-Z])/', '\\1 \\2', $text);
        $text = strtolower($text);

        return $text;
    }

    /**
     * @return void
     */
    public function test()
    {
        $actorClass = $this->getMetadata()->getCurrent('actor');
        /** @var \Codeception\Actor $actor */
        $actor = new $actorClass($this->getScenario());

        if ($actor instanceof FixturesExporterInterface) {
            $actor->exportFixtures($this->executeTestMethod($actor));

            $this->triggerSprykerRuntimeEvents($actor);
        }
    }

    /**
     * @param \Codeception\Actor $I
     *
     * @throws \Codeception\Exception\TestRuntimeException
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    protected function executeTestMethod(Actor $I): FixturesContainerInterface
    {
        if (!method_exists($this->testClassInstance, $this->testMethod)) {
            throw new TestRuntimeException("Method {$this->testMethod} can't be found in tested class");
        }

        return call_user_func([$this->testClassInstance, $this->testMethod], $I, $this->scenario);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return sprintf(
            '%s: %s',
            ReflectionHelper::getClassShortName($this->getTestClass()),
            Message::ucfirst($this->getFeature())
        );
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return get_class($this->getTestClass()) . ':' . $this->getTestMethod();
    }

    /**
     * @return object
     */
    public function getTestClass()
    {
        return $this->testClassInstance;
    }

    /**
     * @return string
     */
    public function getTestMethod(): string
    {
        return $this->testMethod;
    }

    /**
     * @return \Codeception\Lib\Parser
     */
    protected function getParser()
    {
        return $this->parser;
    }

    /**
     * @param \Codeception\Actor $actor
     *
     * @return void
     */
    protected function triggerSprykerRuntimeEvents(Actor $actor): void
    {
        if (method_exists($actor, 'getLocator')) {
            $actor->getLocator()->eventBehavior()->facade()->triggerRuntimeEvents();
        }
    }
}
