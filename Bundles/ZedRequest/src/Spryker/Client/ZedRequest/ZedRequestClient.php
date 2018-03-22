<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * @method \Spryker\Client\ZedRequest\ZedRequestFactory getFactory()
 */
class ZedRequestClient extends AbstractClient implements ZedRequestClientInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\Client\ZedClient
     */
    private $zedClient;

    /**
     * @return \Spryker\Client\ZedRequest\Client\ZedClient
     */
    private function getClient()
    {
        if ($this->zedClient === null) {
            $this->zedClient = $this->getFactory()->createClient();
        }

        return $this->zedClient;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $url
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $object
     * @param array|int|null $requestOptions
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function call($url, TransferInterface $object, $requestOptions = null)
    {
        $localeName = Store::getInstance()->getCurrentLocale();
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($localeName);

        $this->getClient()->addMetaTransfer('locale', $localeTransfer);

        $this->applyMetaData($object);

        return $this->getClient()->call($url, $object, $requestOptions);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseInfoMessages()
    {
        if (!$this->getClient()->hasLastResponse()) {
            return [];
        }

        return $this->getClient()->getLastResponse()->getInfoMessages();
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseErrorMessages()
    {
        if (!$this->getClient()->hasLastResponse()) {
            return [];
        }

        return $this->getClient()->getLastResponse()->getErrorMessages();
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseSuccessMessages()
    {
        if (!$this->getClient()->hasLastResponse()) {
            return [];
        }

        return $this->getClient()->getLastResponse()->getSuccessMessages();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $requestTransfer
     *
     * @return void
     */
    protected function applyMetaData(TransferInterface $requestTransfer)
    {
        $plugins = $this->getFactory()->getMetaDataProviderPlugins();

        foreach ($plugins as $key => $plugin) {
            $this->getClient()->addMetaTransfer(
                $key,
                $plugin->getRequestMetaData($requestTransfer)
            );
        }
    }
}
