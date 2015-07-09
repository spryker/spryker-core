<?php

namespace ProjectA\OpenStack\LoadBalancer;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;
use ProjectA\OpenStack\Factory;

class ListCommand extends Command
{

    const CLI_OPTION_VERBOSE = 'verbose';
    const CLI_OPTION_USERNAME = 'username';
    const CLI_OPTION_API_KEY = 'apiKey';

    /**
     * @var \Symfony\Component\Console\Application
     */
    protected $application;

    /**
     * @param null $name
     * @param Application $application
     */
    public function __construct($name = null, Application $application)
    {
        parent::__construct($name);
        $this->application = $application;
    }

    protected function configure()
    {
        $this->setName('loadBalancer:list')
             ->setDescription('Show all configured load balancers');

        $this->addOption(
            self::CLI_OPTION_USERNAME,
            null,
            InputOption::VALUE_REQUIRED,
            'Add this option to override the username which is defined in config.ini'
        );

        $this->addOption(
            self::CLI_OPTION_API_KEY,
            null,
            InputOption::VALUE_REQUIRED,
            'Add this option to override the apiKey which is defined in config.ini'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loadBalancerService = Factory::getLoadBalancerService($input->getOption(self::CLI_OPTION_USERNAME), $input->getOption(self::CLI_OPTION_API_KEY));
        $loadBalancers = $loadBalancerService->loadBalancerList();

        $helper = new Helper();

        if ($input->getOption(self::CLI_OPTION_VERBOSE)) {
            $helper->dumpLoadBalancerTable($loadBalancers, $output, $this->application);
        } else {
            $data = [];

            foreach ($loadBalancers as $loadBalancer) {
                $data[] = [
                    'name' => $loadBalancer->name(),
                    'addresses' => $helper->getVirtualIps($loadBalancer),
                ];
            }
            $output->writeln(json_encode($data));
        }
    }

}
