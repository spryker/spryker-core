<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Newsletter;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Newsletter\Zed\NewsletterStub;

class NewsletterFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Newsletter\Zed\NewsletterStubInterface
     */
    public function createZedNewsletterStub()
    {
        return new NewsletterStub(
            $this->getProvidedDependency(NewsletterDependencyProvider::SERVICE_ZED),
        );
    }
}
