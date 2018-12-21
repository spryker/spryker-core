<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesReclamation\Business\SalesReclamationBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface getRepository()
 */
class SalesReclamationFacade extends AbstractFacade implements SalesReclamationFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer
     *
     * @return null|\Generated\Shared\Transfer\ReclamationTransfer
     */
    public function createReclamation(ReclamationCreateRequestTransfer $reclamationCreateRequestTransfer): ?ReclamationTransfer
    {
        return $this->getFactory()
            ->createReclamationWriter()
            ->createReclamation($reclamationCreateRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function updateReclamation(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        return $this->getFactory()
            ->createReclamationWriter()
            ->updateReclamation($reclamationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function updateReclamationItem(ReclamationItemTransfer $reclamationItemTransfer): ReclamationItemTransfer
    {
        return $this->getFactory()
            ->createReclamationWriter()
            ->updateReclamationItem($reclamationItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function expandReclamation(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        return $this->getFactory()
            ->createReclamationExpander()
            ->expandReclamation($reclamationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function mapOrderToReclamation(OrderTransfer $orderTransfer, ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        return $this->getFactory()
            ->createReclamationMapper()
            ->mapOrderToReclamation($orderTransfer, $reclamationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationItemTransfer $reclamationItemTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationItemTransfer
     */
    public function getReclamationItemById(ReclamationItemTransfer $reclamationItemTransfer): ReclamationItemTransfer
    {
        return $this->getFactory()->createReclamationReader()->getReclamationItemById($reclamationItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer
     */
    public function getReclamationById(ReclamationTransfer $reclamationTransfer): ReclamationTransfer
    {
        return $this->getFactory()->createReclamationReader()->getReclamationById($reclamationTransfer);
    }
}
