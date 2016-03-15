<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Internal;

use Orm\Zed\Newsletter\Persistence\SpyNewsletterType;
use Spryker\Zed\Installer\Business\Model\AbstractInstaller;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface;

class NewsletterTypeInstaller extends AbstractInstaller
{

    /**
     * @var \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var array
     */
    protected $newsletterTypeCollection;

    /**
     * @param \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface $queryContainer
     * @param array $newsletterTypeCollection
     */
    public function __construct(NewsletterQueryContainerInterface $queryContainer, array $newsletterTypeCollection)
    {
        $this->queryContainer = $queryContainer;
        $this->newsletterTypeCollection = $newsletterTypeCollection;
    }

    /**
     * @return void
     */
    public function install()
    {
        if ($this->queryContainer->queryNewsletterType()->count() > 0) {
            return;
        }

        $this->installNewsletterTypes();
    }

    /**
     * @return void
     */
    protected function installNewsletterTypes()
    {
        foreach ($this->newsletterTypeCollection as $newsletterTypeName) {
            $newsletterType = new SpyNewsletterType();
            $newsletterType->setName($newsletterTypeName);
            $newsletterType->save();
        }
    }

}
