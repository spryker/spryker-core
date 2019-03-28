<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishingExtension\Dependency;

interface PublishingRegistryInterface
{

    /**
     * @param PublishingCollectionInterface $publishingCollection
     *
     * @return PublishingCollectionInterface
     */
    public function getRegisteredPublishingCollection(PublishingCollectionInterface $publishingCollection);
}
