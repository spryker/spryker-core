<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantProfileMerchantTableDataExpanderPlugin extends AbstractPlugin implements MerchantTableDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands merchant table data.
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expand(array $item): array
    {
        $merchantProfileCriteriaFilterTransfer = new MerchantProfileCriteriaFilterTransfer();
        $merchantProfileCriteriaFilterTransfer->setFkMerchant($item[SpyMerchantTableMap::COL_ID_MERCHANT]);
        $merchantProfileTransfer = $this->getFactory()
            ->getMerchantProfileFacade()
            ->findOne($merchantProfileCriteriaFilterTransfer);

        return [$this->getConfig()->getIsActiveColumnName() => $this->getIsActiveLabel($merchantProfileTransfer)];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer|null $merchantProfileTransfer
     *
     * @return string
     */
    protected function getIsActiveLabel(?MerchantProfileTransfer $merchantProfileTransfer): string
    {
        if ($merchantProfileTransfer && $merchantProfileTransfer->getIsActive()) {
            return $this->generateLabel('Active', 'label-info');
        }

        return $this->generateLabel('Inactive', 'label-danger');
    }

    /**
     * @param string $title
     * @param string|null $class
     *
     * @return string
     */
    protected function generateLabel(string $title, ?string $class): string
    {
        return $this->getFactory()->getTwigEnvironment()->render('label.twig', [
            'title' => $title,
            'class' => $class,
        ]);
    }
}
