<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\Solr;

use Solarium\Core\Query\QueryInterface;

/**
 * Class LocalInterface
 */
interface LocalInterface
{

    /**
     * @param string $type
     * @param array $options
     *
     * @return \Solarium\Core\Query\Query
     */
    public function createQuery($type, $options = null);

    /**
     * @param QueryInterface $query
     *
     * @return \Solarium\Core\Query\Result\ResultInterface
     */
    public function execute(QueryInterface $query);

    /**
     * @param $coreName
     */
    public function reloadCore($coreName);

    /**
     * @param $coreName
     * @param $coreDir
     */
    public function createCore($coreName, $coreDir);

    /**
     * @param $coreName
     */
    public function unloadCore($coreName);

}
