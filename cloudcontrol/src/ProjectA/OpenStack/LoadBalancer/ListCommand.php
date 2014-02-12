<?php

namespace ProjectA\OpenStack\LoadBalancer;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;
use ProjectA\OpenStack\Factory;

class ListCommand extends Command
{

    const CLI_OPTION_VERBOSE = 'verbose';

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
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loadBalancerService = Factory::getLoadBalancerService();
        $loadBalancers = $loadBalancerService->loadBalancerList();

        $helper = new Helper();

        if ($input->getOption(self::CLI_OPTION_VERBOSE)) {
            $helper->dumpLoadBalancerTable($loadBalancers, $output, $this->application);
        } else {
            $data = [];

            foreach ($loadBalancers as $loadBalancer) {
                $data[] = [
                    'name' => $loadBalancer->name(),
                    'addresses' => $helper->getVirtualIps($loadBalancer)
                ];
            }
            $output->writeln(json_encode($data));
        }
    }
}
