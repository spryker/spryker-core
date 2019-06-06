<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Console\Migrate;

use InvalidArgumentException;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * @method \Spryker\Zed\Twig\Business\TwigFacadeInterface getFacade()
 * @method \Spryker\Zed\Twig\Communication\TwigCommunicationFactory getFactory()
 */
class TwigNamespaceMigratorConsole extends Console
{
    public const COMMAND_NAME = 'twig:migrate:namespace';
    public const DESCRIPTION = 'This command will migrate all PSR-0 to PSR-4 namespaces.';
    public const ARGUMENT_PATH = 'path';

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION)
            ->addArgument(static::ARGUMENT_PATH, InputArgument::REQUIRED, 'Path to files which could contain PSR-0 namespaces for Twig.');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $numberOfChangedFiles = $this->searchAndReplace();

        if ($numberOfChangedFiles === 0) {
            $output->writeln('No files where changed.');

            return static::CODE_SUCCESS;
        }

        $output->writeln(sprintf('Changed <fg=yellow>%s</> files.', $numberOfChangedFiles));

        return static::CODE_SUCCESS;
    }

    /**
     * @return int
     */
    protected function searchAndReplace(): int
    {
        $searchAndReplaceMap = $this->buildSearchAndReplaceMap();
        $search = array_keys($searchAndReplaceMap);
        $replace = array_values($searchAndReplaceMap);

        $changedFiles = 0;

        foreach ($this->getFinder() as $splFileInfo) {
            $fileContent = $splFileInfo->getContents();
            $changedFileContent = str_replace($search, $replace, $fileContent);

            if ($fileContent !== $changedFileContent) {
                $this->output->writeln($splFileInfo->getRealPath());
                file_put_contents($splFileInfo->getRealPath(), $changedFileContent);
                $changedFiles++;
            }
        }

        return $changedFiles;
    }

    /**
     * @return array
     */
    protected function buildSearchAndReplaceMap(): array
    {
        $searchAndReplace = [];

        foreach ($this->buildNamespaceMap() as $psr0 => $psr4) {
            $psr4ClassNameParts = explode('\\', $psr4);
            $shortClassName = array_pop($psr4ClassNameParts);
            $searchAndReplace[sprintf('use %s;', $psr0)] = sprintf('use %s;', $psr4);
            $searchAndReplace[sprintf('\%s', $psr0)] = sprintf('\%s', $psr4);
            $searchAndReplace[$psr0] = $shortClassName;
        }

        return $searchAndReplace;
    }

    /**
     * @return array
     */
    protected function buildNamespaceMap(): array
    {
        $finder = new Finder();
        $finder->in(APPLICATION_VENDOR_DIR . '/twig/twig/src/')->files();

        $namespaceMap = [];

        foreach ($finder as $splFileInfo) {
            $fileContent = $splFileInfo->getContents();

            if (preg_match_all('/class_alias\(\'(.*?)\', \'(.*?)\'\);/', $fileContent, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    if (strpos($match[1], '_') !== false) {
                        $namespaceMap[$match[1]] = $match[2];

                        continue;
                    }

                    $namespaceMap[$match[2]] = $match[1];
                }
            }

            if (preg_match_all('/class_alias\(\'(.*?)\', \'(.*?)\', (.*?)\);/', $fileContent, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    if (strpos($match[1], '_') !== false) {
                        $namespaceMap[$match[1]] = $match[2];

                        continue;
                    }

                    $namespaceMap[$match[2]] = $match[1];
                }
            }
        }

        return $namespaceMap;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder(): Finder
    {
        $directory = APPLICATION_ROOT_DIR . '/' . ltrim($this->input->getArgument(static::ARGUMENT_PATH), '/');

        if (!is_dir($directory)) {
            throw new InvalidArgumentException(sprintf('Given "%s" is not a directory', $directory));
        }

        $finder = new Finder();
        $finder->in($directory)->files()->contains('/Twig_/');

        return $finder;
    }
}
