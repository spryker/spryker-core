<?php

namespace SprykerTest\Zed\Translator;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class TranslatorBusinessTester extends \Codeception\Actor
{
    use _generated\TranslatorBusinessTesterActions;

    /**
     * Define custom actions here
     */

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
