<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Communication;

use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Monitoring\Communication\Plugin\ControllerListener;
use Spryker\Zed\Monitoring\Communication\Plugin\GatewayControllerListener;
use Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeInterface;
use Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToStoreFacadeInterface;
use Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface;
use Spryker\Zed\Monitoring\MonitoringDependencyProvider;

/**
 * @method \Spryker\Zed\Monitoring\Business\MonitoringFacadeInterface getFacade()
 * @method \Spryker\Zed\Monitoring\MonitoringConfig getConfig()
 */
class MonitoringCommunicationFactory extends AbstractCommunicationFactory
{
    public function createGatewayControllerListener(): GatewayControllerListener
    {
        return new GatewayControllerListener(
            $this->getMonitoringService(),
            $this->getUtilNetworkService(),
            $this->getLocaleFacade(),
        );
    }

    public function createControllerListener(): ControllerListener
    {
        return new ControllerListener(
            $this->getMonitoringService(),
            $this->getStoreFacade(),
            $this->getLocaleFacade(),
            $this->getUtilNetworkService(),
            $this->getConfig()->getIgnorableTransactions(),
        );
    }

    public function getMonitoringService(): MonitoringServiceInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::MONITORING_SERVICE);
    }

    public function getStoreFacade(): MonitoringToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::FACADE_STORE);
    }

    public function getLocaleFacade(): MonitoringToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::FACADE_LOCALE);
    }

    public function getUtilNetworkService(): MonitoringToUtilNetworkServiceInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::SERVICE_UTIL_NETWORK);
    }
}
