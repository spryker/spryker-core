<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishingExtension\Dependency;

interface PublisherRegistryInterface
{

    /**
     * @param PublisherCollectionInterface $publisherCollection
     *
     * @return PublisherCollectionInterface
     */
    public function getRegisteredPublishers(PublisherCollectionInterface $publisherCollection);
}
