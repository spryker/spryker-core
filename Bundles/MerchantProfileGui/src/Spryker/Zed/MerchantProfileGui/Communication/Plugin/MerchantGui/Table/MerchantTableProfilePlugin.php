<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table;

use Generated\Shared\Transfer\ButtonTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableActionExpanderPluginInterface;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableConfigExpanderPluginInterface;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableDataExpanderPluginInterface;
use Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableHeaderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantTableProfilePlugin extends AbstractPlugin implements MerchantTableActionExpanderPluginInterface, MerchantTableDataExpanderPluginInterface, MerchantTableHeaderExpanderPluginInterface, MerchantTableConfigExpanderPluginInterface
{
    protected const COL_IS_ACTIVE = 'is_active';
    protected const COL_IS_ACTIVE_LABEL = 'active';
    protected const ID_MERCHANT = SpyMerchantTableMap::COL_ID_MERCHANT;

    protected const REQUEST_ID_MERCHANT_PROFILE = 'id-merchant-profile';
    protected const URL_MERCHANT_PROFILE_ACTIVATE = '/merchant-profile-gui/edit/activate';
    protected const URL_MERCHANT_PROFILE_DEACTIVATE = '/merchant-profile-gui/edit/deactivate';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        $config->addRawColumn(static::COL_IS_ACTIVE);

        return $config;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function expandHeader(): array
    {
        return [static::COL_IS_ACTIVE => static::COL_IS_ACTIVE_LABEL];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        return [static::COL_IS_ACTIVE => $this->getIsActive($item)];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function getActionButtonDefinitions(array $item): array
    {
        $buttons = [];
        $activeButton = $this->getAtiveButton($item);

        if ($activeButton) {
            $buttons[] = $activeButton;
        }

        return $buttons;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getIsActive(array $item): string
    {
        $merchantProfileTransfer = $this->getMerchantProfileByIdMerchant($item[static::ID_MERCHANT]);

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

    /**
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer|null
     */
    protected function getAtiveButton(array $item): ?ButtonTransfer
    {
        $merchantProfileTransfer = $this->getMerchantProfileByIdMerchant($item[static::ID_MERCHANT]);
        if ($merchantProfileTransfer === null) {
            return null;
        }
        $idMerchantProfile = $merchantProfileTransfer->getIdMerchantProfile();

        if ($merchantProfileTransfer->getIsActive()) {
            return (new ButtonTransfer())
                ->setUrl(Url::generate(
                    static::URL_MERCHANT_PROFILE_DEACTIVATE,
                    [static::REQUEST_ID_MERCHANT_PROFILE => $idMerchantProfile]
                ))
                ->setTitle('Deactivate')
                ->setDefaultOptions([
                    'class' => 'btn-remove',
                    'icon' => 'fa fa-trash',
                ]);
        }

        return (new ButtonTransfer())
            ->setUrl(Url::generate(
                static::URL_MERCHANT_PROFILE_ACTIVATE,
                [static::REQUEST_ID_MERCHANT_PROFILE => $idMerchantProfile]
            ))
            ->setTitle('Activate')
            ->setDefaultOptions([
                'class' => 'btn-view',
                'icon' => 'fa fa-caret-right',
            ]);
    }

    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    protected function getMerchantProfileByIdMerchant(int $idMerchant): ?MerchantProfileTransfer
    {
        $merchantProfileCriteriaFilterTransfer = new MerchantProfileCriteriaFilterTransfer();
        $merchantProfileCriteriaFilterTransfer->setIdMerchant($idMerchant);

        return $this->getFactory()->getMerchantProfileFacade()->findOne($merchantProfileCriteriaFilterTransfer);
    }
}
