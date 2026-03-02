<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceBridge;

/**
 * @method \Spryker\Yves\Monitoring\MonitoringConfig getConfig()
 */
class MonitoringDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const MONITORING_SERVICE = 'monitoring service';

    /**
     * @var string
     */
    public const SERVICE_NETWORK = 'util network service';

    public function provideDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addUtilNetworkService($container);

        return $container;
    }

    protected function addMonitoringService(Container $container): Container
    {
        $container->set(static::MONITORING_SERVICE, function (Container $container) {
            return $container->getLocator()->monitoring()->service();
        });

        return $container;
    }

    protected function addUtilNetworkService(Container $container): Container
    {
        $container->set(static::SERVICE_NETWORK, function (Container $container) {
            $monitoringToUtilNetworkServiceBridge = new MonitoringToUtilNetworkServiceBridge(
                $container->getLocator()->utilNetwork()->service(),
            );

            return $monitoringToUtilNetworkServiceBridge;
        });

        return $container;
    }
}
