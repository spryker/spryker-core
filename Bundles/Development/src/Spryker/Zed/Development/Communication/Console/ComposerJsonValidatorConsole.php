<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Generated\Shared\Transfer\ComposerJsonValidationRequestTransfer;
use Generated\Shared\Transfer\ComposerJsonValidationResponseTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 *
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 */
class ComposerJsonValidatorConsole extends AbstractCoreModuleAwareConsole
{
    protected const COMMAND_NAME = 'dev:composer:validate-json-files';

    /**
     * @var int
     */
    protected $composerJsonErrorCount = 0;

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Validates composer.json files from core modules.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $modulesToValidate = $this->getModulesToExecute($input);

        if (!$this->canRun($modulesToValidate)) {
            return static::CODE_ERROR;
        }

        $this->validateModulesComposerJson($modulesToValidate);

        if ($this->composerJsonErrorCount > 0) {
            $output->writeln(sprintf('Found <fg=red>%s</> composer.json errors', $this->composerJsonErrorCount));

            return static::CODE_ERROR;
        }

        $output->writeln('No composer.json errors found');

        return static::CODE_SUCCESS;
    }

    /**
     * @param array $modulesToValidate
     *
     * @return void
     */
    protected function validateModulesComposerJson(array $modulesToValidate): void
    {
        foreach ($modulesToValidate as $collectionKey => $moduleTransfer) {
            if (!$this->isNamespacedModuleName($collectionKey)) {
                continue;
            }

            $this->validateModuleComposerJson($moduleTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return void
     */
    protected function validateModuleComposerJson(ModuleTransfer $moduleTransfer): void
    {
        $composerJsonValidationRequestTransfer = new ComposerJsonValidationRequestTransfer();
        $composerJsonValidationRequestTransfer->setModule($moduleTransfer);

        $composerJsonValidationResponseTransfer = $this->getFacade()->validateComposerJson($composerJsonValidationRequestTransfer);

        $composerJsonErrorCount = count($composerJsonValidationResponseTransfer->getValidationMessages());
        $this->composerJsonErrorCount += $composerJsonErrorCount;

        if ($composerJsonErrorCount > 0 && $this->output->isVerbose()) {
            $this->showErrors($moduleTransfer, $composerJsonValidationResponseTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param \Generated\Shared\Transfer\ComposerJsonValidationResponseTransfer $composerJsonValidationResponseTransfer
     *
     * @return void
     */
    protected function showErrors(ModuleTransfer $moduleTransfer, ComposerJsonValidationResponseTransfer $composerJsonValidationResponseTransfer)
    {
        $this->output->writeln(sprintf('<fg=green>%s</> has <fg=red>%s</> composer.json errors', $this->buildModuleKey($moduleTransfer), count($composerJsonValidationResponseTransfer->getValidationMessages())));

        foreach ($composerJsonValidationResponseTransfer->getValidationMessages() as $validationMessageTransfer) {
            $this->output->writeln($validationMessageTransfer->getMessage());
        }

        $this->output->writeln('');
    }
}
