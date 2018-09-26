<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NewRelic\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\NewRelic\Business\NewRelicFacadeInterface getFacade()
 * @method \Spryker\Zed\NewRelic\Communication\NewRelicCommunicationFactory getFactory()
 */
class RecordDeploymentConsole extends Console
{
    public const COMMAND_NAME = 'newrelic:record-deployment';
    public const DESCRIPTION = 'Send deployment notification to New Relic';

    public const ARGUMENT_APPLICATION_NAME = 'app_name';
    public const ARGUMENT_APPLICATION_NAME_DESCRIPTION = 'The name of the application in New Relic';

    public const ARGUMENT_USER = 'user';
    public const ARGUMENT_USER_DESCRIPTION = 'The name of the deployer';

    public const ARGUMENT_REVISION = 'revision';
    public const ARGUMENT_REVISION_DESCRIPTION = 'Revision number';

    public const ARGUMENT_DESCRIPTION = 'description';
    public const ARGUMENT_DESCRIPTION_DESCRIPTION = 'Deployment description';

    public const ARGUMENT_CHANGELOG = 'changelog';
    public const ARGUMENT_CHANGELOG_DESCRIPTION = 'Change log';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addArgument(
            self::ARGUMENT_APPLICATION_NAME,
            InputArgument::REQUIRED,
            self::ARGUMENT_APPLICATION_NAME_DESCRIPTION
        );

        $this->addArgument(
            self::ARGUMENT_USER,
            InputArgument::OPTIONAL,
            self::ARGUMENT_USER_DESCRIPTION
        );

        $this->addArgument(
            self::ARGUMENT_REVISION,
            InputArgument::OPTIONAL,
            self::ARGUMENT_REVISION_DESCRIPTION
        );

        $this->addArgument(
            self::ARGUMENT_DESCRIPTION,
            InputArgument::OPTIONAL,
            self::ARGUMENT_DESCRIPTION_DESCRIPTION
        );

        $this->addArgument(
            self::ARGUMENT_CHANGELOG,
            InputArgument::OPTIONAL,
            self::ARGUMENT_CHANGELOG_DESCRIPTION
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getMessenger()->info(sprintf(
            'Send deployment notification to New Relic for %s',
            $input->getArgument(self::ARGUMENT_APPLICATION_NAME)
        ));

        $arguments = $input->getArguments();
        unset($arguments['command']);

        $this->getFacade()->recordDeployment($arguments);

        return 0;
    }
}
