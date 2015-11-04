<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\NewRelic\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\NewRelic\Communication\NewRelicDependencyContainer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method NewRelicDependencyContainer getDependencyContainer()
 */
class RecordDeploymentConsole extends Console
{

    const COMMAND_NAME = 'newrelic:record-deployment';
    const DESCRIPTION = 'Send deployment notification to New Relic';

    const ARGUMENT_APPLICATION_NAME = 'app_name';
    const ARGUMENT_APPLICATION_NAME_DESCRIPTION = 'The name of the application in New Relic';

    const ARGUMENT_USER = 'user';
    const ARGUMENT_USER_DESCRIPTION = 'The name of the deployer';

    const ARGUMENT_REVISION = 'revision';
    const ARGUMENT_REVISION_DESCRIPTION = 'Revision number';

    const ARGUMENT_DESCRIPTION = 'description';
    const ARGUMENT_DESCRIPTION_DESCRIPTION = 'Deployment description';

    const ARGUMENT_CHANGELOG = 'changelog';
    const ARGUMENT_CHANGELOG_DESCRIPTION = 'Change log';

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
     * @param InputInterface $input
     * @param OutputInterface $output
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

        $newRelicApi = $this->getDependencyContainer()->createNewRelicApi();
        $newRelicApi->recordDeployment($arguments);

        return 0;
    }

}
