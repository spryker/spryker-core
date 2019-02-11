<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Fixtures;

use Codeception\Configuration;
use Codeception\Exception\ConfigurationException;

trait FixturesTrait
{
    /**
     * @return \Codeception\Scenario
     */
    abstract protected function getScenario();

    /**
     * @param string $className
     *
     * @throws \Codeception\Exception\ConfigurationException
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function loadFixtures(string $className): FixturesContainerInterface
    {
        $filename = $this->getFixturesFileName($className);

        if (!file_exists($filename)) {
            throw new ConfigurationException(sprintf(
                'Fixtures file could not be loaded: %s. Make sure that `codecept fixtures` has been run before.',
                $filename
            ));
        }

        $object = unserialize(
            file_get_contents($filename),
            ['allowed_classes' => true]
        );

        foreach ($object->steps as $step) {
            $this->getScenario()->addStep($step);
        }

        return $object->fixtures;
    }

    /**
     * @param \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface $fixture
     *
     * @throws \Codeception\Exception\ConfigurationException
     *
     * @return void
     */
    public function exportFixtures(FixturesContainerInterface $fixture): void
    {
        $filename = $this->getFixturesFileName(get_class($fixture));

        $object = (object)[
            'fixtures' => $fixture,
            'steps' => $this->getScenario()->getSteps(),
        ];

        $fileOperationResult = file_put_contents($filename, serialize($object));

        if ($fileOperationResult === false) {
            throw new ConfigurationException(sprintf('Fixtures file could not be written: %s', $filename));
        }
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function getFixturesFileName(string $className): string
    {
        $path = Configuration::supportDir() . '_generated' . DIRECTORY_SEPARATOR;

        return sprintf(
            '%s%s.fixtures',
            $path,
            str_replace('\\', '_', $className)
        );
    }
}
