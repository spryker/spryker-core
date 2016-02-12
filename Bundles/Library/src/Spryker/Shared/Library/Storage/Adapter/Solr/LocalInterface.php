<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Storage\Adapter\Solr;

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
     * @param \Solarium\Core\Query\QueryInterface $query
     *
     * @return \Solarium\Core\Query\Result\ResultInterface
     */
    public function execute(QueryInterface $query);

    /**
     * @param string $coreName
     */
    public function reloadCore($coreName);

    /**
     * @param string $coreName
     * @param string $coreDir
     */
    public function createCore($coreName, $coreDir);

    /**
     * @param string $coreName
     */
    public function unloadCore($coreName);

}
