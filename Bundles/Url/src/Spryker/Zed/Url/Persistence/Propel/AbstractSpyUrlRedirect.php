<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence\Propel;

use Orm\Zed\Url\Persistence\Base\SpyUrlRedirect as BaseSpyUrlRedirect;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Url\Business\Exception\RedirectLoopException;

/**
 * Skeleton subclass for representing a row from the 'spy_redirect' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyUrlRedirect extends BaseSpyUrlRedirect
{

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con
     *
     * @return bool
     */
    public function preSave(ConnectionInterface $con = null)
    {
        $result = parent::preSave($con);

        $this->assertRedirectLoops();

        return $result;
    }

    /**
     * @throws \Spryker\Zed\Url\Business\Exception\RedirectLoopException
     *
     * @return void
     */
    protected function assertRedirectLoops()
    {
        foreach ($this->getSpyUrls() as $urlEntity) {
            if ($urlEntity->getUrl() === $this->getToUrl()) {
                throw new RedirectLoopException(sprintf(
                    'Redirecting "%s" to "%s" resolves in a URL redirect loop.',
                    $urlEntity->getUrl(),
                    $this->getToUrl()
                ));
            }
        }
    }

}
