<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Business;

use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Monitoring\Business\EventHandler\EventHandler;
use Spryker\Zed\Monitoring\Business\EventHandler\EventHandlerInterface;
use Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface;
use Spryker\Zed\Monitoring\MonitoringDependencyProvider;

/**
 * @method \Spryker\Zed\Monitoring\MonitoringConfig getConfig()
 */
class MonitoringBusinessFactory extends AbstractBusinessFactory
{
    public function createEventHandler(): EventHandlerInterface
    {
        return new EventHandler(
            $this->getMonitoringService(),
            $this->getUtilNetworkService(),
            $this->getMonitoringTransactionNamingStrategies(),
        );
    }

    /**
     * @return array<\Spryker\Zed\Monitoring\Business\MonitoringTransactionNamingStrategy\MonitoringTransactionNamingStrategyInterface>
     */
    public function getMonitoringTransactionNamingStrategies(): array
    {
        return [];
    }

    public function getMonitoringService(): MonitoringServiceInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::MONITORING_SERVICE);
    }

    public function getUtilNetworkService(): MonitoringToUtilNetworkServiceInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::SERVICE_UTIL_NETWORK);
    }
}
