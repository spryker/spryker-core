<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CustomersRestApi\CustomersRestApiFactory getFactory()
 */
class SetCustomerBeforeActionPlugin extends AbstractPlugin implements ControllerBeforeActionPluginInterface
{
    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function beforeAction(string $action, RestRequestInterface $restRequest): void
    {
        if (!$restRequest->getUser()) {
            return;
        }

        $user = $restRequest->getUser();

        //workaround for customer data, most clients use session client to retrieve customer data.
        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer((int)$user->getSurrogateIdentifier())
            ->setIsDirty(false)
            ->setCustomerReference($user->getNaturalIdentifier());

        $this->getFactory()
            ->getSessionClient()
            ->set('customer data', $customerTransfer);
    }
}
