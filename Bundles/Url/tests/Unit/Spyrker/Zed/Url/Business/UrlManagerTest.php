<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Url\Business;

use Functional\Spryker\Zed\ProductOption\Mock\LocaleFacade;
use Spryker\Zed\Touch\Business\TouchFacade;
use Spryker\Zed\Url\Business\UrlManager;
use Spryker\Zed\Url\Dependency\UrlToLocaleBridge;
use Spryker\Zed\Url\Dependency\UrlToTouchBridge;
use Spryker\Zed\Url\Persistence\UrlQueryContainer;

/**
 * @group Spryker
 * @group Zed
 * @group Url
 * @group Business
 */
class UrlManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var \Spryker\Zed\Url\Dependency\UrlToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Url\Dependency\UrlToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @var \Spryker\Zed\Url\Business\UrlManagerInterface
     */
    protected $urlManager;

    const SLUG_VALUE = 'foO # @ # # Bar';

    protected function setUp()
    {
        parent::setUp();

        $this->urlQueryContainer = new UrlQueryContainer();
        $this->localeFacade = new LocaleFacade();
        $this->touchFacade = new TouchFacade();
        $this->connection = $this->urlQueryContainer->getConnection();

        $this->urlManager = new UrlManager(
            $this->urlQueryContainer,
            new UrlToLocaleBridge($this->localeFacade),
            new UrlToTouchBridge($this->touchFacade),
            $this->connection
        );
    }

}
