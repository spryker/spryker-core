<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Mail;

use Spryker\Zed\Mail\Business\Exception\MailNotFoundException;
use Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface;

class MailTypeCollection implements MailTypeCollectionAddInterface, MailTypeCollectionGetInterface
{
    /**
     * @var array
     */
    protected $mailTypes;

    /**
     * @param \Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface $mailType
     *
     * @return $this
     */
    public function add(MailTypePluginInterface $mailType)
    {
        $this->mailTypes[$mailType->getName()] = $mailType;

        return $this;
    }

    /**
     * @param string $mailType
     *
     * @return bool
     */
    public function has($mailType)
    {
        return isset($this->mailTypes[$mailType]);
    }

    /**
     * @param string $mailType
     *
     * @throws \Spryker\Zed\Mail\Business\Exception\MailNotFoundException
     *
     * @return \Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface
     */
    public function get($mailType)
    {
        if ($this->has($mailType)) {
            return $this->mailTypes[$mailType];
        }

        throw new MailNotFoundException(sprintf(
            'No mail by type "%s" found in MailCollection. Please use MailDependencyProvider to add the expected Mails.',
            $mailType
        ));
    }
}
