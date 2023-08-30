<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\InvalidationVoter;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface;

class InvalidationVoter implements InvalidationVoterInterface
{
    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface
     */
    protected $dynamicEntityFacade;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface $dynamicEntityFacade
     */
    public function __construct(DynamicEntityBackendApiToDynamicEntityFacadeInterface $dynamicEntityFacade)
    {
        $this->dynamicEntityFacade = $dynamicEntityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer
     *
     * @return bool
     */
    public function isInvalidated(DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer): bool
    {
        $dateInterval = DateInterval::createFromDateString($documentationInvalidationVoterRequestTransfer->getIntervalOrFail());

        if (!$dateInterval) {
            return false;
        }

        $currentDateTime = (new DateTime())->sub($dateInterval);
        $criteriaRangeFilterTransfer = (new CriteriaRangeFilterTransfer())->setFrom($currentDateTime->format(static::DATE_TIME_FORMAT));

        $dynamicEntityConfigurationConditionsTransfer = (new DynamicEntityConfigurationConditionsTransfer())
            ->setIsActive(true)
            ->setFilterUpdatedAt($criteriaRangeFilterTransfer)
            ->setFilterUpdatedAt($criteriaRangeFilterTransfer);

        $dynamicEntityConfigurationCriteriaTransfer = new DynamicEntityConfigurationCriteriaTransfer();
        $dynamicEntityConfigurationCriteriaTransfer->setDynamicEntityConfigurationConditions(
            $dynamicEntityConfigurationConditionsTransfer,
        );

        $dynamicEntityConfigurationCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityConfigurationCollection($dynamicEntityConfigurationCriteriaTransfer);

        return $dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations()->count() > 0;
    }
}
