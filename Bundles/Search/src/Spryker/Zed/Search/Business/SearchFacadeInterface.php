<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

interface SearchFacadeInterface
{

    /**
     * @api
     *
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger);

    /**
     * @api
     *
     * @return int
     */
    public function getTotalCount();

    /**
     * @api
     *
     * @return array
     */
    public function getMetaData();

    /**
     * @api
     *
     * @return \Elastica\Response
     */
    public function delete();

    /**
     * @api
     *
     * @param string $key
     * @param string $type
     *
     * @return \Elastica\Document
     */
    public function getDocument($key, $type);

    /**
     * @api
     *
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return \Elastica\ResultSet
     */
    public function searchKeys($searchString, array $requestParameters = []);

}
