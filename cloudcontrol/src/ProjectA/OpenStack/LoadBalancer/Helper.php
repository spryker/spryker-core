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
     * @return \OpenCloud\Common\Collection
     */
    protected function getLoadBalancers()
    {
        $loadBalancerService = Factory::getLoadBalancerService();
        return $loadBalancerService->loadBalancerList();
    }

    /**
     * @param string $loadBalancerName
     * @return bool
     */
    public function loadBalancerExists($loadBalancerName)
    {
        return null !== $this->getLoadBalancerByName($loadBalancerName);
    }

    /**
     * @param string $loadBalancerName
     * @return null|LoadBalancer
     */
    public function getLoadBalancerByName($loadBalancerName) {
        $loadBalancers = $this->getLoadBalancers();

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
     * @param null $type
     * @param null $weight
     * @return bool
     */
    public function createNode($loadBalancerName, $address, $port, $condition = 'ENABLED', $type = null, $weight = null)
    {
        $loadBalancer = $this->getLoadBalancerByName($loadBalancerName);
        $status = $loadBalancer->status;

        if ($this->isLoadBalancerActive($loadBalancerName)) {
            $loadBalancer->addNode($address, $port, $condition, $type, $weight);
            $loadBalancer->addNodes();

            return true;
        }

        return false;
    }

    /**
     * @param string $loadBalancerName
     * @param Node $nodeToDelete
     * @return bool
     */
    public function deleteNode($loadBalancerName, Node $nodeToDelete)
    {
        if ($this->isLoadBalancerActive($loadBalancerName)) {
            $nodeToDelete->delete();
            return true;
        }

        return false;
    }

    /**
     * @param string $loadBalancerName
     * @return bool
     */
    protected function isLoadBalancerActive($loadBalancerName)
    {
        $loadBalancer = $this->getLoadBalancerByName($loadBalancerName);

        return $loadBalancer->status == self::LOAD_BALANCER_STATUS_ACTIVE;
    }
}
