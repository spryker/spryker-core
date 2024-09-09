<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IncrementalInstaller\Communication\Console;

use Exception;
use Generated\Shared\Transfer\IncrementalInstallerCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer;
use Generated\Shared\Transfer\IncrementalInstallerConditionsTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\IncrementalInstaller\Communication\IncrementalInstallerCommunicationFactory getFactory()
 * @method \Spryker\Zed\IncrementalInstaller\Business\IncrementalInstallerFacadeInterface getFacade()
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerRepositoryInterface getRepository()
 */
class IncrementalInstallersRollbackConsole extends Console
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'incremental-installer:rollback';

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
    protected const ROLL_BACK_INSTALLERS_MESSAGE = 'There are the next pending incremental installers to rollback:';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE = 'Rolled back %s incremental installers!';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'There were errors during the rolling back of incremental installers:';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PLACEHOLDER = 'Installer: %s, Error: %s';

    /**
     * @return void
     */
    public function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addOption(static::OPT_DRY_RUN, null, InputOption::VALUE_NONE, 'Do not rollback installers');
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
        $installersToRollback = $this->getInstallersToRollback();

        $this->info(static::ROLL_BACK_INSTALLERS_MESSAGE);
        foreach ($installersToRollback as $pendingInstaller) {
            $this->info(get_class($pendingInstaller));
        }

        if ($dryRun === true) {
            return static::CODE_SUCCESS;
        }

        $this->rollbackInstallers($installersToRollback);

        $this->success(sprintf(static::SUCCESS_MESSAGE, count($installersToRollback)));

        return static::CODE_SUCCESS;
    }

    /**
     * @return array<\Spryker\Zed\IncrementalInstallerExtension\Dependency\Plugin\IncrementalInstallerPluginInterface>
     */
    protected function getInstallersToRollback(): array
    {
        $lastBatch = $this->getRepository()->getLastBatch();
        $incrementalInstallerConditionsTransfer = (new IncrementalInstallerConditionsTransfer())
            ->setBatch($lastBatch);
        $incrementalInstallerCriteriaTransfer = (new IncrementalInstallerCriteriaTransfer())
            ->setIncrementalInstallerConditions($incrementalInstallerConditionsTransfer);

        $installersToRollbackCollection = $this->getFacade()->getIncrementalInstallerCollection($incrementalInstallerCriteriaTransfer);
        $installersToRollbackNames = [];
        /** @var array<\Spryker\Zed\IncrementalInstallerExtension\Dependency\Plugin\IncrementalInstallerPluginInterface> $installersToRollback */
        $installersToRollback = [];
        foreach ($installersToRollbackCollection->getIncrementalInstallers() as $incrementalInstallerTransfer) {
            $installersToRollbackNames[] = $incrementalInstallerTransfer->getInstaller();
        }
        foreach ($installersToRollbackNames as $installersToRollbackName) {
            $installersToRollback[] = new $installersToRollbackName();
        }

        return $installersToRollback;
    }

    /**
     * @param array<\Spryker\Zed\IncrementalInstallerExtension\Dependency\Plugin\IncrementalInstallerPluginInterface> $installersToRollback
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer
     */
    protected function rollbackInstallers(array $installersToRollback): IncrementalInstallerCollectionResponseTransfer
    {
        $incrementalInstallerCollectionDeleteCriteriaTransfer = new IncrementalInstallerCollectionDeleteCriteriaTransfer();

        try {
            foreach ($installersToRollback as $installerToRollback) {
                $installerToRollback->rollback();
                $incrementalInstallerCollectionDeleteCriteriaTransfer->addIncrementalInstallerName(get_class($installerToRollback));
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        } finally {
            return $this->getFacade()->deleteIncrementalInstallerCollection($incrementalInstallerCollectionDeleteCriteriaTransfer);
        }
    }
}
