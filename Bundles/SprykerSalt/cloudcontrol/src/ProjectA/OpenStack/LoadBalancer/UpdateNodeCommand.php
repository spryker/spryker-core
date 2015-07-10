<?php

namespace ProjectA\OpenStack\LoadBalancer;

use OpenCloud\Common\Exceptions\InstanceNotFound;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;

class UpdateNodeCommand extends Command
{

    const CLI_OPTION_DRY_RUN = 'dry-run';
    const CLI_OPTION_USERNAME = 'username';
    const CLI_OPTION_API_KEY = 'apiKey';
    const CLI_OPTION_CREATE = 'create';
    const CLI_OPTION_DELETE = 'delete';
    const CLI_ARGUMENT_LOAD_BALANCER_NAME = 'loadBalancer';
    const CLI_ARGUMENT_IP_ADDRESSES_AND_PORTS = 'ipAddressesAndPorts';
    const KEY_ADDRESS = 'address';
    const KEY_PORT = 'port';
    const ACTION_CREATE = 'create';
    const ACTION_DELETE = 'delete';
    const CONDITION_ENABLED = 'ENABLED';

    /**
     * @var \Symfony\Component\Console\Application
     */
    protected $application;

    /**
     * @param null|string $name
     * @param Application $application
     */
    public function __construct($name = null, Application $application)
    {
        parent::__construct($name);
        $this->application = $application;
    }

    protected function configure()
    {
        $this->setName('loadBalancer:update-nodes')
             ->setDescription('Updates a given load balancer (--create and --delete nodes)')
             ->addArgument(
                self::CLI_ARGUMENT_LOAD_BALANCER_NAME,
                InputArgument::REQUIRED,
                'Set the loadbalancer\' name'
             )
             ->addArgument(
                self::CLI_ARGUMENT_IP_ADDRESSES_AND_PORTS,
                InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'List all destination IP addresses and ports, e.g. 10.10.10.1:8080 10.10.10.2:8080 ...'
            )
             ->addOption(
                self::CLI_OPTION_CREATE,
                null,
                InputOption::VALUE_NONE,
                'Add this option to perform the creation of load balancer nodes'
            )
            ->addOption(
                self::CLI_OPTION_DELETE,
                null,
                InputOption::VALUE_NONE,
                'Add this option to perform the deletion of load balancer nodes'
            )
            ->addOption(
                self::CLI_OPTION_DRY_RUN,
                null,
                InputOption::VALUE_NONE,
                'Add this option just to see how the load balancer would be configured without changing anything'
            )
            ->addOption(
                self::CLI_OPTION_USERNAME,
                null,
                InputOption::VALUE_REQUIRED,
                'Add this option to override the username which is defined in config.ini'
            )
            ->addOption(
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
     * @throws \OpenCloud\Common\Exceptions\InstanceNotFound
     * @throws \InvalidArgumentException
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loadBalancerName = $input->getArgument(self::CLI_ARGUMENT_LOAD_BALANCER_NAME);
        $username = $input->getOption(self::CLI_OPTION_USERNAME);
        $apiKey = $input->getOption(self::CLI_OPTION_API_KEY);
        $config = [];

        foreach ($input->getArgument(self::CLI_ARGUMENT_IP_ADDRESSES_AND_PORTS) as $ipAndPort) {
            if (!strstr($ipAndPort, ':')) {
                throw new \InvalidArgumentException($ipAndPort . ' does not contain a port number!');
            }

            if (substr_count($ipAndPort, '.') !== 3) {
                throw new \InvalidArgumentException($ipAndPort . ' does not look like a valid IPv4 address!');
            }

            $parts = explode(':', $ipAndPort);

            $config[] = [
                self::KEY_ADDRESS => $parts[0],
                self::KEY_PORT => $parts[1],
            ];
        }

        $helper = new Helper();
        $loadBalancer = $helper->getLoadBalancerByName($loadBalancerName, $username, $apiKey);

        if (null === $loadBalancer) {
            throw new InstanceNotFound('Cannot find load balancer: ' . $loadBalancerName);
        }

        $nodes = $helper->getNodes($loadBalancer);
        $nodesToDelete = [];
        $nodesToCreate = [];
        $nodeData = [];

        foreach ($nodes as $node) {
            $address = $node->address;
            $port = $node->port;

            $nodeData[] = [
                self::KEY_ADDRESS => $address,
                self::KEY_PORT => $port,
            ];

            if (!$this->isInConfig($config, $address, $port)) {
                $nodesToDelete[] = $node;
            }
        }

        foreach ($config as $pair) {
            if (!$this->isInConfig($nodeData, $pair[self::KEY_ADDRESS], $pair[self::KEY_PORT])) {
                $nodesToCreate[] = $pair;
            }
        }

        /*
         * Check command line options (create, delete, dry-run)
         */
        if ($input->getOption(self::CLI_OPTION_DRY_RUN)) {
            $this->showTasks($nodesToCreate, $nodesToDelete, $output);
            exit(0);
        }

        $result = [];
        $result['created']['count'] = 0;
        $result['deleted']['count'] = 0;

        if ($input->getOption(self::CLI_OPTION_CREATE)) {
            foreach ($nodesToCreate as $node) {
                while ($helper->createNode(
                        $loadBalancerName,
                        $node[self::KEY_ADDRESS],
                        $node[self::KEY_PORT],
                        self::CONDITION_ENABLED,
                        $username,
                        $apiKey
                    ) !== true) {
                    sleep(1);
                }
                $result['created']['nodes'] = [
                    'address' => $node[self::KEY_ADDRESS],
                    'port' => $node[self::KEY_PORT],
                ];
                $result['created']['count'] += 1;
            }
        }

        if ($input->getOption(self::CLI_OPTION_DELETE)) {
            foreach ($nodesToDelete as $nodeToDelete) {
                while ($helper->deleteNode($loadBalancerName, $nodeToDelete, $username, $apiKey) !== true) {
                    sleep(1);
                }

                $result['deleted']['nodes'] = [
                    'address' => $nodeToDelete->address,
                    'port' => $nodeToDelete->port,
                ];
                $result['deleted']['count'] += 1;
            }
        }

        $output->writeln(json_encode($result));
    }

    /**
     * @param array $config
     * @param string $address
     * @param string $port
     *
     * @return bool
     */
    protected function isInConfig($config, $address, $port)
    {
        foreach ($config as $pair) {

            if (($pair[self::KEY_ADDRESS] === $address) && ($pair[self::KEY_PORT] === $port)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $nodesToCreate
     * @param array $nodesToDelete
     * @param OutputInterface $output
     */
    protected function showTasks(array $nodesToCreate, array $nodesToDelete, OutputInterface $output)
    {
        $data = [];

        foreach ($nodesToCreate as $nodeToCreate) {
            $data[] = [
                $nodeToCreate[self::KEY_ADDRESS],
                $nodeToCreate[self::KEY_PORT],
                self::ACTION_CREATE,
            ];
        }

        foreach ($nodesToDelete as $nodeToDelete) {
            $data[] = [
                $nodeToDelete->address,
                $nodeToDelete->port,
                self::ACTION_DELETE,
            ];
        }

        if (count($data) === 0) {
            $output->writeln('Nothing to do');

            return;
        }

        $table = $this->application->getHelperSet()->get('table');
        $table
            ->setHeaders(['Address', 'Port', 'Action'])
            ->setRows($data);
        $table->render($output);
    }

}
