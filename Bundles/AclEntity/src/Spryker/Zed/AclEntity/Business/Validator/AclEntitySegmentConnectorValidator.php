<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business\Validator;

use Spryker\Service\AclEntity\AclEntityServiceInterface;
use Spryker\Zed\AclEntity\Business\Exception\ReferencedSegmentConnectorEntityNotFoundException;
use Spryker\Zed\AclEntity\Business\Exception\SegmentConnectorEntityNotFoundException;

class AclEntitySegmentConnectorValidator implements AclEntitySegmentConnectorValidatorInterface
{
    /**
     * @var \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    protected $aclEntityService;

    /**
     * @param \Spryker\Service\AclEntity\AclEntityServiceInterface $aclEntityService
     */
    public function __construct(AclEntityServiceInterface $aclEntityService)
    {
        $this->aclEntityService = $aclEntityService;
    }

    /**
     * @param string $entity
     *
     * @return void
     */
    public function validate(string $entity): void
    {
        $this->validateEntity($entity);
        $segmentConnectorClassName = $this->aclEntityService->generateSegmentConnectorClassName($entity);
        $this->validateConnectorEntity($segmentConnectorClassName);
    }

    /**
     * @param string $entity
     *
     * @throws \Spryker\Zed\AclEntity\Business\Exception\ReferencedSegmentConnectorEntityNotFoundException
     *
     * @return void
     */
    protected function validateEntity(string $entity): void
    {
        if (!class_exists($entity)) {
            throw new ReferencedSegmentConnectorEntityNotFoundException($entity);
        }
    }

    /**
     * @param string $segmentConnectorClassName
     *
     * @throws \Spryker\Zed\AclEntity\Business\Exception\SegmentConnectorEntityNotFoundException
     *
     * @return void
     */
    protected function validateConnectorEntity(string $segmentConnectorClassName): void
    {
        if (!class_exists($segmentConnectorClassName)) {
            throw new SegmentConnectorEntityNotFoundException($segmentConnectorClassName);
        }
    }
}
