<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Provider;

use Spryker\Zed\Mail\MailConfig;
use Spryker\Zed\MailExtension\Dependency\Plugin\MailProviderPluginInterface;

class MailProviderCollection implements MailProviderCollectionAddInterface, MailProviderCollectionGetInterface
{
    /**
     * @var string
     */
    public const ACCEPTED_MAIL_TYPES = 'accepted mail types';

    /**
     * @var string
     */
    public const PROVIDER = 'provider';

    /**
     * @var array
     */
    protected $mailProvider;

    /**
     * @param \Spryker\Zed\MailExtension\Dependency\Plugin\MailProviderPluginInterface $mailProvider
     * @param array|string $acceptedMailTypes
     *
     * @return $this
     */
    public function addProvider(MailProviderPluginInterface $mailProvider, $acceptedMailTypes)
    {
        if (!is_array($acceptedMailTypes)) {
            $acceptedMailTypes = [$acceptedMailTypes];
        }

        $this->mailProvider[] = [
            static::PROVIDER => $mailProvider,
            static::ACCEPTED_MAIL_TYPES => $acceptedMailTypes,
        ];

        return $this;
    }

    /**
     * @param string $mailType
     *
     * @return array<\Spryker\Zed\MailExtension\Dependency\Plugin\MailProviderPluginInterface>
     */
    public function getProviderForMailType($mailType)
    {
        $mailProviderForMailType = [];
        foreach ($this->mailProvider as $provider) {
            if (in_array(MailConfig::MAIL_TYPE_ALL, $provider[static::ACCEPTED_MAIL_TYPES]) || in_array($mailType, $provider[static::ACCEPTED_MAIL_TYPES])) {
                $mailProviderForMailType[] = $provider[static::PROVIDER];
            }
        }

        return $mailProviderForMailType;
    }
}
