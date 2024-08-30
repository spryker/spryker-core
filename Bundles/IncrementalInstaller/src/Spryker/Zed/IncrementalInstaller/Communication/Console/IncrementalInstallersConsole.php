<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IncrementalInstaller\Communication\Console;

use Exception;
use Generated\Shared\Transfer\IncrementalInstallerCollectionRequestTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCriteriaTransfer;
use Generated\Shared\Transfer\IncrementalInstallerTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\IncrementalInstaller\Communication\IncrementalInstallerCommunicationFactory getFactory()
 * @method \Spryker\Zed\IncrementalInstaller\Business\IncrementalInstallerFacadeInterface getFacade()
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerRepositoryInterface getRepository()
 */
class IncrementalInstallersConsole extends Console
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'incremental-installer:execute';

    /**
     * @var string
     */
    protected const COMMAND_DESCRIPTION = 'Execute incremental installers';

    /**
     * @var string
     */
    protected const OPT_DRY_RUN = 'dry-run';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PLACEHOLDER = 'Installer: %s, Error: %s';

    /**
     * @var string
     */
    protected const PENDING_INSTALLERS_MESSAGE = 'There are the next pending incremental installers to be executed:';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE = 'Executed %s incremental installers!';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'There were errors during the execution of incremental installers:';

    /**
     * @return void
     */
    public function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addOption(static::OPT_DRY_RUN, null, InputOption::VALUE_NONE, 'Do not execute installers');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dryRun = $input->getOption(static::OPT_DRY_RUN);
        $pendingInstallers = $this->getPendingInstallers();

        $this->info(static::PENDING_INSTALLERS_MESSAGE);
        foreach ($pendingInstallers as $pendingInstaller) {
            $this->info(get_class($pendingInstaller));
        }

        if ($dryRun === true) {
            return static::CODE_SUCCESS;
        }

        $incrementalInstallerCollectionResponseTransfer = $this->executePendingInstallers($pendingInstallers);

        if ($incrementalInstallerCollectionResponseTransfer->getErrors()->count() > 0) {
            $this->handleErrors($incrementalInstallerCollectionResponseTransfer);

            return static::CODE_ERROR;
        }

        $this->success(sprintf(static::SUCCESS_MESSAGE, count($pendingInstallers)));

        return static::CODE_SUCCESS;
    }

    /**
     * @return array<\Spryker\Zed\IncrementalInstallerExtension\Dependency\Plugin\IncrementalInstallerPluginInterface>
     */
    protected function getPendingInstallers(): array
    {
        $incrementalInstallerPlugins = $this->getFactory()->getIncrementalInstallerPlugins();
        $incrementalInstallerNames = array_map(
            static function ($incrementalInstallerPlugin) {
                return get_class($incrementalInstallerPlugin);
            },
            $incrementalInstallerPlugins,
        );

        $executedInstallersCollection = $this->getFacade()->getIncrementalInstallerCollection(new IncrementalInstallerCriteriaTransfer());
        $executedInstallerNames = [];
        $pendingInstallers = [];
        foreach ($executedInstallersCollection->getIncrementalInstallers() as $incrementalInstallerTransfer) {
            $executedInstallerNames[] = $incrementalInstallerTransfer->getInstaller();
        }
        $pendingInstallerNames = array_diff($incrementalInstallerNames, $executedInstallerNames);
        foreach ($pendingInstallerNames as $pendingInstallerName) {
            $pendingInstallers[] = new $pendingInstallerName();
        }

        return $pendingInstallers;
    }

    /**
     * @param array<\Spryker\Zed\IncrementalInstallerExtension\Dependency\Plugin\IncrementalInstallerPluginInterface> $pendingInstallers
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer
     */
    protected function executePendingInstallers(array $pendingInstallers): IncrementalInstallerCollectionResponseTransfer
    {
        $lastBatch = $this->getRepository()->getLastBatch();
        $batch = ++$lastBatch;

        $incrementalInstallerCollectionRequestTransfer = new IncrementalInstallerCollectionRequestTransfer();

        try {
            foreach ($pendingInstallers as $pendingInstaller) {
                if ($pendingInstaller->isApplicable() === false) {
                    continue;
                }
                $pendingInstaller->execute();
                $incrementalInstallerTransfer = (new IncrementalInstallerTransfer())
                    ->setInstaller(get_class($pendingInstaller))
                    ->setBatch($batch);
                $incrementalInstallerCollectionRequestTransfer->addIncrementalInstaller($incrementalInstallerTransfer);
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        } finally {
            return $this->getFacade()->createIncrementalInstallerCollection($incrementalInstallerCollectionRequestTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer $incrementalInstallerCollectionResponseTransfer
     *
     * @return void
     */
    protected function handleErrors(IncrementalInstallerCollectionResponseTransfer $incrementalInstallerCollectionResponseTransfer): void
    {
        $this->error(static::ERROR_MESSAGE);
        foreach ($incrementalInstallerCollectionResponseTransfer->getErrors() as $error) {
            $this->error(sprintf(static::ERROR_MESSAGE_PLACEHOLDER, $error->getEntityIdentifier(), $error->getMessage()));
        }
    }
}
