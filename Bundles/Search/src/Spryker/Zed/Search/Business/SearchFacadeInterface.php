<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search\Business;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

interface SearchFacadeInterface
{

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger);

    /**
     * @return int
     */
    public function getTotalCount();

    /**
     * @return array
     */
    public function getMetaData();

    /**
     * @return \Elastica\Response
     */
    public function delete();

    /**
     * @param string $key
     * @param string $type
     *
     * @return \Elastica\Document
     */
    public function getDocument($key, $type);

}
