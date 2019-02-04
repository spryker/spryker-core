<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Translator;

use Codeception\Actor;
use Spryker\Service\Translator\TranslatorServiceInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class TranslatorServiceTester extends Actor
{
    use _generated\TranslatorServiceTesterActions;

    /**
     * @return \Spryker\Service\Translator\TranslatorServiceInterface
     */
    public function getService(): TranslatorServiceInterface
    {
        return $this->getLocator()->translator()->service();
    }

    /**
     * @return void
     */
    public function clearOutputDirectory(): void
    {
        $directory = codecept_output_dir();

        if (!file_exists($directory)) {
            return;
        }

        $fileSystem = new Filesystem();
        $fileSystem->remove($this->findFiles($directory));
    }

    /**
     * @param string $directory
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function findFiles(string $directory): Finder
    {
        $finder = new Finder();
        $finder
            ->in($directory)
            ->depth(0);

        return $finder;
    }
}
