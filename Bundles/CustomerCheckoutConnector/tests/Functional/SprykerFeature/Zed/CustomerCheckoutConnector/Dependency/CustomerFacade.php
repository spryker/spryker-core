<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\CustomerCheckoutConnector\Dependency;

use SprykerFeature\Zed\Customer\Business\CustomerFacade as SprykerCustomerFacade;
use SprykerFeature\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface;

class CustomerFacade extends SprykerCustomerFacade implements CustomerCheckoutConnectorToCustomerInterface
{

}
