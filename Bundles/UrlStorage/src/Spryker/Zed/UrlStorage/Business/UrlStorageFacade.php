<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\UrlStorage\Business\UrlStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStorageRepositoryInterface getRepository()
 */
class UrlStorageFacade extends AbstractFacade implements UrlStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $urlIds
     *
     * @return void
     */
    public function publishUrl(array $urlIds)
    {
        $this->getFactory()->createUrlStorageWriter()->publish($urlIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $urlIds
     *
     * @return void
     */
    public function unpublishUrl(array $urlIds)
    {
        $this->getFactory()->createUrlStorageWriter()->unpublish($urlIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $redirectIds
     *
     * @return void
     */
    public function publishRedirect(array $redirectIds)
    {
        $this->getFactory()->createRedirectStorageWriter()->publish($redirectIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $redirectIds
     *
     * @return void
     */
    public function unpublishRedirect(array $redirectIds)
    {
        $this->getFactory()->createRedirectStorageWriter()->unpublish($redirectIds);
    }
}
