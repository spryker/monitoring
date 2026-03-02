<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Monitoring;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class MonitoringDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const MONITORING_EXTENSIONS = 'monitoring extensions';

    public function provideServiceDependencies(Container $container): Container
    {
        $container = $this->addMonitoringExtensions($container);

        return $container;
    }

    protected function addMonitoringExtensions(Container $container): Container
    {
        // Usually we make use of a closure here. This is not possible here as we use the MonitoringService in our SessionHandler which are part of the Request object.
        // In another place we make use of the serialize function which will break if we would use the closure here. This will be fixed soon with an upcoming bug ticket.
        $container->set(static::MONITORING_EXTENSIONS, $this->getMonitoringExtensions());

        return $container;
    }

    /**
     * @return array<\Spryker\Service\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface>
     */
    protected function getMonitoringExtensions(): array
    {
        return [];
    }
}
