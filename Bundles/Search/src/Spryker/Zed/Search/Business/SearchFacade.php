<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search\Business;

use Elastica\Document;
use Elastica\Response;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method SearchBusinessFactory getBusinessFactory
 */
class SearchFacade extends AbstractFacade
{

    /**
     * @param MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getBusinessFactory()->createSearchInstaller($messenger)->install();
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getBusinessFactory()->createSearch()->getTotalCount();
    }

    /**
     * @return array
     */
    public function getMetaData()
    {
        return $this->getBusinessFactory()->createSearch()->getMetaData();
    }

    /**
     * @return Response
     */
    public function delete()
    {
        return $this->getBusinessFactory()->createSearch()->delete();
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return Document
     */
    public function getDocument($key, $type)
    {
        return $this->getBusinessFactory()->createSearch()->getDocument($key, $type);
    }

}
