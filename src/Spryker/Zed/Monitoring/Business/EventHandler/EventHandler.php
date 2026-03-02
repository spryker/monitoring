<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Business\EventHandler;

use Error;
use Generated\Shared\Transfer\MonitoringTransactionEventTransfer;
use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

class EventHandler implements EventHandlerInterface
{
    /**
     * @var string
     */
    protected const TRANSACTION_NAME_PREFIX = 'vendor/bin/console ';

    /**
     * @var string
     */
    protected const PARAMETER_HOST = 'host';

    /**
     * @var \Spryker\Service\Monitoring\MonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @var \Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface
     */
    protected $utilNetworkService;

    /**
     * @var array<\Spryker\Zed\Monitoring\Business\MonitoringTransactionNamingStrategy\MonitoringTransactionNamingStrategyInterface>
     */
    protected array $monitoringTransactionNamingStrategies = [];

    /**
     * @param \Spryker\Service\Monitoring\MonitoringServiceInterface $monitoringService
     * @param \Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface $utilNetworkService
     * @param array<\Spryker\Zed\Monitoring\Business\MonitoringTransactionNamingStrategy\MonitoringTransactionNamingStrategyInterface> $monitoringTransactionNamingStrategies
     */
    public function __construct(
        MonitoringServiceInterface $monitoringService,
        MonitoringToUtilNetworkServiceInterface $utilNetworkService,
        array $monitoringTransactionNamingStrategies = []
    ) {
        $this->monitoringService = $monitoringService;
        $this->utilNetworkService = $utilNetworkService;
        $this->monitoringTransactionNamingStrategies = $monitoringTransactionNamingStrategies;
    }

    public function handleConsoleTerminateEvent(ConsoleTerminateEvent $event): void
    {
        $this->monitoringService->markAsConsoleCommand();
        $this->monitoringService->setTransactionName($this->getTransactionName($event));
        $this->monitoringService->addCustomParameter(static::PARAMETER_HOST, $this->utilNetworkService->getHostName());

        $this->addArgumentsAsCustomParameter($event);
        $this->addOptionsAsCustomParameter($event);
    }

    protected function getTransactionName(ConsoleTerminateEvent $event): string
    {
        try {
            $monitoringTransactionEventTransfer = $this->mapConsoleTerminateEventToMonitoringTransactionEventTransfer($event);
        } catch (Error $e) {
            return static::TRANSACTION_NAME_PREFIX . $event->getCommand()->getName();
        }

        foreach ($this->monitoringTransactionNamingStrategies as $monitoringTransactionNamingStrategy) {
            if ($monitoringTransactionNamingStrategy->isApplicable($monitoringTransactionEventTransfer)) {
                return $monitoringTransactionNamingStrategy->getMonitoringTransactionName($monitoringTransactionEventTransfer);
            }
        }

        return static::TRANSACTION_NAME_PREFIX . $event->getCommand()->getName();
    }

    protected function mapConsoleTerminateEventToMonitoringTransactionEventTransfer(
        ConsoleTerminateEvent $event
    ): MonitoringTransactionEventTransfer {
        $monitoringTransactionEventTransfer = new MonitoringTransactionEventTransfer();
        $monitoringTransactionEventTransfer->setCommandName($event->getCommand()->getName());
        $monitoringTransactionEventTransfer->setArguments($event->getInput()->getArguments());
        $monitoringTransactionEventTransfer->setTransactionNamePrefix(trim(static::TRANSACTION_NAME_PREFIX));

        return $monitoringTransactionEventTransfer;
    }

    protected function addArgumentsAsCustomParameter(ConsoleTerminateEvent $event): void
    {
        $this->addCustomParameter($event->getInput()->getArguments());
    }

    protected function addOptionsAsCustomParameter(ConsoleTerminateEvent $event): void
    {
        $this->addCustomParameter($event->getInput()->getOptions());
    }

    protected function addCustomParameter(array $customParameter): void
    {
        foreach ($customParameter as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $this->monitoringService->addCustomParameter($key, $value);
        }
    }
}
