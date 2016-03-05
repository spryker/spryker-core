<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

/**
 * @method \Spryker\Zed\Search\Business\SearchBusinessFactory getFactory()
 */
class SearchFacade extends AbstractFacade implements SearchFacadeInterface
{

    /**
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getFactory()->createElasticsearchIndexInstaller()->install();
    }

    /**
     * @api
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getFactory()->createSearch()->getTotalCount();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getMetaData()
    {
        return $this->getFactory()->createSearch()->getMetaData();
    }

    /**
     * @api
     *
     * @return \Elastica\Response
     */
    public function delete()
    {
        return $this->getFactory()->createSearch()->delete();
    }

    /**
     * @api
     *
     * @param string $key
     * @param string $type
     *
     * @return \Elastica\Document
     */
    public function getDocument($key, $type)
    {
        return $this->getFactory()->createSearch()->getDocument($key, $type);
    }

}
