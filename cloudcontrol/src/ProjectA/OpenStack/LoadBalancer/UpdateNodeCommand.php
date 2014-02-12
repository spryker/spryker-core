<?php

namespace ProjectA\OpenStack\LoadBalancer;

use OpenCloud\Common\Exceptions\InstanceNotFound;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;

class UpdateNodeCommand extends Command
{

    const CLI_OPTION_DRY_RUN = 'dry-run';
    const CLI_ARGUMENT_LOAD_BALANCER_NAME = 'loadBalancer';
    const CLI_ARGUMENT_IP_ADDRESSES_AND_PORTS = 'ipAddressesAndPorts';
    const CLI_OPTION_CREATE = 'create';
    const CLI_OPTION_DELETE = 'delete';
    const KEY_ADDRESS = 'address';
    const KEY_PORT = 'port';
    const ACTION_CREATE = 'create';
    const ACTION_DELETE = 'delete';

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
             ->setDescription('Updates a given load balancer (create and delete nodes)')
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
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \OpenCloud\Common\Exceptions\InstanceNotFound
     * @throws \InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $loadBalancerName = $input->getArgument(self::CLI_ARGUMENT_LOAD_BALANCER_NAME);
        $config = [];

        foreach ($input->getArgument(self::CLI_ARGUMENT_IP_ADDRESSES_AND_PORTS) as $ipAndPort) {
            if (!strstr($ipAndPort, ':')) {
                throw new \InvalidArgumentException($ipAndPort . ' does not contain a port number!');
            }

            if (substr_count($ipAndPort, '.') != 3) {
                throw new \InvalidArgumentException($ipAndPort . ' does not look like a valid IPv4 address!');
            }

            $parts = explode(':', $ipAndPort);

            $config[] = [
                self::KEY_ADDRESS => $parts[0],
                self::KEY_PORT => $parts[1]
            ];
        }

        $helper = new Helper();
        $loadBalancer = $helper->getLoadBalancerByName($loadBalancerName);

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
                self::KEY_PORT => $port
            ];

            if (! $this->isInConfig($config, $address, $port)) {
                $nodesToDelete[] = $node;
            }
        }

        foreach ($config as $pair) {
            if (! $this->isInConfig($nodeData, $pair[self::KEY_ADDRESS], $pair[self::KEY_PORT])) {
                $nodesToCreate[] = $pair;
            }
        }

        /**
         * Check command line options (create, delete, dry-run)
         */
        if ($input->getOption(self::CLI_OPTION_DRY_RUN)) {
            $this->showTasks($nodesToCreate, $nodesToDelete, $output);
            exit(0);
        }

        $result = [];
        if ($input->getOption(self::CLI_OPTION_CREATE)) {
            foreach ($nodesToCreate as $node) {
                while (($status = $helper->createNode($loadBalancerName, $node[self::KEY_ADDRESS], $node[self::KEY_PORT])) !== true) {
                    sleep(1);
                }
                $result['created'][] = [
                    'address' => $node[self::KEY_ADDRESS],
                    'port' => $node[self::KEY_PORT]
                ];
                $result['created']['count'] += 1;
            }
        } else {
            $result['created']['count'] = 0;
        }

        if ($input->getOption(self::CLI_OPTION_DELETE)) {
            foreach ($nodesToDelete as $nodeToDelete) {
                while (($status = $helper->deleteNode($loadBalancerName, $nodeToDelete)) !== true) {
                    sleep(1);
                }

                $result['created'][] = [
                    'address' => $nodeToDelete->address,
                    'port' => $nodeToDelete->port
                ];
                $result['deleted']['count'] += 1;
            }
        } else {
            $result['deleted']['count'] = 0;
        }
    }

    /**
     * @param array $config
     * @param string $address
     * @param string $port
     * @return bool
     */
    protected function isInConfig($config, $address, $port)
    {
        foreach ($config as $pair) {

            if (($pair[self::KEY_ADDRESS] == $address) && ($pair[self::KEY_PORT] == $port)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $data
     * @param OutputInterface $output
     */
    protected function dumpJsonResult($data, OutputInterface $output)
    {
        $output->writeln(json_encode($data), $output);
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
                self::ACTION_CREATE
            ];
        }

        foreach ($nodesToDelete as $nodeToDelete) {
            $data[] = [
                $nodeToDelete->address,
                $nodeToDelete->port,
                self::ACTION_DELETE
            ];
        }

        if (count($data) == 0) {
            $output->writeln('Nothing to do');
            return;
        }

        $table = $this->application->getHelperSet()->get('table');
        $table
            ->setHeaders(array('Address', 'Port', 'Action'))
            ->setRows($data);
        $table->render($output);
    }
}
