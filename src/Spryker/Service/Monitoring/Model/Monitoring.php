<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Monitoring\Model;

use Spryker\Service\MonitoringExtension\Dependency\Plugin\CustomEventsMonitoringExtensionPluginInterface;

class Monitoring implements MonitoringInterface
{
    /**
     * @var bool
     */
    protected static $isApplicationNameSet = false;

    /**
     * @var array<\Spryker\Service\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface>
     */
    protected $monitoringExtensionPlugins;

    /**
     * @param array<\Spryker\Service\MonitoringExtension\Dependency\Plugin\MonitoringExtensionPluginInterface> $monitoringExtensionPlugins
     */
    public function __construct(array $monitoringExtensionPlugins)
    {
        $this->monitoringExtensionPlugins = $monitoringExtensionPlugins;
    }

    /**
     * @param string $message
     * @param \Exception|\Throwable $exception
     *
     * @return void
     */
    public function setError(string $message, $exception): void
    {
        $this->setApplicationName();

        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->setError($message, $exception);
        }
    }

    public function setApplicationName(?string $application = null, ?string $store = null, ?string $environment = null): void
    {
        if (static::$isApplicationNameSet) {
            return;
        }

        $application = $application ?: APPLICATION;

        if ($store === null) {
            $store = defined('APPLICATION_STORE') ? APPLICATION_STORE : null;
        }

        $environment = $environment ?: APPLICATION_ENV;

        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->setApplicationName($application, $store, $environment);
        }

        static::$isApplicationNameSet = true;
    }

    public function setTransactionName(string $name): void
    {
        $this->setApplicationName();

        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->setTransactionName($name);
        }
    }

    public function markStartTransaction(): void
    {
        $this->setApplicationName();
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->markStartTransaction();
        }
    }

    public function markEndOfTransaction(): void
    {
        $this->setApplicationName();
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->markEndOfTransaction();
        }
    }

    public function markIgnoreTransaction(): void
    {
        $this->setApplicationName();
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->markIgnoreTransaction();
        }
    }

    public function markAsConsoleCommand(): void
    {
        $this->setApplicationName();
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->markAsConsoleCommand();
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addCustomParameter(string $key, $value): void
    {
        $this->setApplicationName();
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->addCustomParameter($key, $value);
        }
    }

    public function addCustomTracer(string $tracer): void
    {
        $this->setApplicationName();
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            $monitoringExtensionPlugin->addCustomTracer($tracer);
        }
    }

    public function addCustomEvent(string $name, array $attributes = []): void
    {
        $this->setApplicationName();
        foreach ($this->monitoringExtensionPlugins as $monitoringExtensionPlugin) {
            if ($monitoringExtensionPlugin instanceof CustomEventsMonitoringExtensionPluginInterface) {
                $monitoringExtensionPlugin->addEvent($name, $attributes);
            }
        }
    }
}
