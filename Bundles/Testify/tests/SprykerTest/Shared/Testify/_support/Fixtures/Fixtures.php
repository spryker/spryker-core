<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Fixtures;

use Codeception\Lib\Parser;
use Codeception\Test\Loader\LoaderInterface;

class Fixtures implements LoaderInterface
{
    public const METHOD_BUILD_FIXTURES = 'buildFixtures';

    /**
     * @var \SprykerTest\Shared\Testify\Fixtures\Fixture[]
     */
    protected $tests = [];

    /**
     * @var array
     */
    protected $settings;

    /**
     * @param array $settings
     */
    public function __construct($settings = [])
    {
        //These are the suite settings
        $this->settings = $settings;
    }

    /**
     * @param string $filename
     *
     * @return void
     */
    public function loadTests($filename): void
    {
        Parser::load($filename);
        $fixtureClasses = Parser::getClassesFromFile($filename);

        foreach ($fixtureClasses as $fixtureClass) {
            $instance = new $fixtureClass();

            if ($instance instanceof FixturesBuilderInterface) {
                $this->tests[] = new Fixture($instance, static::METHOD_BUILD_FIXTURES, $filename);
            }
        }
    }

    /**
     * @return \SprykerTest\Shared\Testify\Fixtures\Fixture[]
     */
    public function getTests(): array
    {
        return $this->tests;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return '~Fixtures\.php$~';
    }
}
