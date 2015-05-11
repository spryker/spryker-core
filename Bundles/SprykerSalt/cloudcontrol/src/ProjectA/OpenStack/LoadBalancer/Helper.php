<?php

namespace ProjectA\OpenStack\LoadBalancer;

use OpenCloud\LoadBalancer\Resource\LoadBalancer;
use OpenCloud\LoadBalancer\Resource\VirtualIp;
use OpenCloud\LoadBalancer\Resource\Node;
use OpenCloud\Common\Collection\PaginatedIterator;
use Symfony\Component\Console\Output\OutputInterface;
use ProjectA\OpenStack\Factory;

class Helper
{

    const CLI_OPTION_VERBOSE = 'verbose';
    const IPv4 = 'IPV4';
    const LOAD_BALANCER_STATUS_ACTIVE = 'ACTIVE';

    /**
     * @param LoadBalancer $loadBalancer
     * @return int
     */
    public function getAmountOfNodes(LoadBalancer $loadBalancer)
    {
        return count($loadBalancer->nodeList());
    }

    /**
     * @param PaginatedIterator $loadBalancers
     * @param OutputInterface $output
     *
     * @param $application
     */
    public function dumpLoadBalancerTable(PaginatedIterator $loadBalancers, OutputInterface $output, $application)
    {
        $data = [];

        foreach ($loadBalancers as $loadBalancer) {
            /** @var LoadBalancer $loadBalancer */

            $data[] = [
                $loadBalancer->name,
                implode(', ', $this->getVirtualIps($loadBalancer)),
                $loadBalancer->protocol . ':' .$loadBalancer->port,
                implode(PHP_EOL, $this->getNodeInfo($loadBalancer))
            ];

        }

        $table = $application->getHelperSet()->get('table');
        $table
            ->setHeaders(array('Name', 'Address', 'Protocol:Port', 'Nodes'))
            ->setRows($data);
        $table->render($output);
    }

    /**
     * @param LoadBalancer $loadBalancer
     * @return array
     */
    public function getNodeInfo(LoadBalancer $loadBalancer)
    {
        $nodeInfo = [];
        foreach ($this->getNodes($loadBalancer) as $node) {
            /** @var Node $node **/
            $nodeInfo[] = $node->address . ' (' . $node->condition . ') => ' . $node->port;
        }

        return $nodeInfo;
    }

    /**
     * @param LoadBalancer $loadBalancer
     * @return array
     */
    public function getNodes(LoadBalancer $loadBalancer)
    {
        $nodes = [];
        foreach ($loadBalancer->nodeList() as $node) {
            /** @var Node $node **/
            $nodes[] = $node;
        }

        return $nodes;
    }

    /**
     * @param LoadBalancer $loadBalancer
     * @return array
     */
    public function getVirtualIps(Loadbalancer $loadBalancer)
    {
        $ips = [];

        foreach ($loadBalancer->virtualIpList() as $virtualIp) {
            /** @var $virtualIp VirtualIp **/
            if ($virtualIp->ipVersion == self::IPv4) {
                $ips[] = $virtualIp->address;
            }
        }

        return $ips;
    }

    /**
     * @param null|string $username
     * @param null|string $apiKey
     * @return \OpenCloud\Common\Collection
     */
    protected function getLoadBalancers($username = null, $apiKey = null)
    {
        $loadBalancerService = Factory::getLoadBalancerService($username, $apiKey);
        return $loadBalancerService->loadBalancerList();
    }

    /**
     * @param string $loadBalancerName
     * @param null|string $username
     * @param null|string $apiKey
     * @return bool
     */
    public function loadBalancerExists($loadBalancerName, $username = null, $apiKey = null)
    {
        return null !== $this->getLoadBalancerByName($loadBalancerName, $username, $apiKey);
    }

    /**
     * @param string $loadBalancerName
     * @param null|string $username
     * @param null|string $apiKey
     * @return null
     */
    public function getLoadBalancerByName($loadBalancerName, $username = null, $apiKey = null) {
        $loadBalancers = $this->getLoadBalancers($username, $apiKey);

        foreach ($loadBalancers as $loadBalancer) {
            if ($loadBalancer->name() == $loadBalancerName) {
                return $loadBalancer;
            }
        }

        return null;
    }

    /**
     * @param string $loadBalancerName
     * @param string $address
     * @param string $port
     * @param string $condition
     * @param null|string $username
     * @param null|string $apiKey
     * @param null|string $type
     * @param null|string $weight
     * @return bool
     */
    public function createNode($loadBalancerName, $address, $port, $condition = 'ENABLED', $username = null, $apiKey = null, $type = null, $weight = null)
    {
        $loadBalancer = $this->getLoadBalancerByName($loadBalancerName, $username, $apiKey);

        if ($this->isLoadBalancerActive($loadBalancerName, $username, $apiKey)) {
            $loadBalancer->addNode($address, $port, $condition, $type, $weight);
            $loadBalancer->addNodes();

            return true;
        }

        return false;
    }

    /**
     * @param string$loadBalancerName
     * @param Node $nodeToDelete
     * @param null|string $username
     * @param null|string $apiKey
     * @return bool
     */
    public function deleteNode($loadBalancerName, Node $nodeToDelete, $username = null, $apiKey = null)
    {
        if ($this->isLoadBalancerActive($loadBalancerName, $username, $apiKey)) {
            $nodeToDelete->delete();
            return true;
        }

        return false;
    }

    /**
     * @param string $loadBalancerName
     * @param null $username
     * @param null $apiKey
     * @return bool
     */
    protected function isLoadBalancerActive($loadBalancerName, $username = null, $apiKey = null)
    {
        $loadBalancer = $this->getLoadBalancerByName($loadBalancerName, $username, $apiKey);

        return $loadBalancer->status == self::LOAD_BALANCER_STATUS_ACTIVE;
    }
}
