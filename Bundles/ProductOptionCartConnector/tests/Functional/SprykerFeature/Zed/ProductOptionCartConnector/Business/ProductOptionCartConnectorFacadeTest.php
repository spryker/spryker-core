<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductOptionCartConnector\Business;

use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerFeature\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade;

/**
 * @group Business
 * @group Zed
 * @group ProductOptionCartConnector
 * @group ProductOptionCartConnectorFacadeTest
 */
class ProductOptionCartConnectorFacadeTest extends AbstractFunctionalTest
{

    /**
     * @var ProductOptionCartConnectorFacade
     */
    private $facade;

    public function setUp()
    {
        parent::setUp();

        $this->facade = $this->getFacade();
    }

    public function testIteWorks()
    {
        // ...
    }
}
