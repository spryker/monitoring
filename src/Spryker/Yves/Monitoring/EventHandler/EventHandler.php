<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring\EventHandler;

use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Yves\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface;
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
     * @var \Spryker\Yves\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface
     */
    protected $utilNetworkService;

    public function __construct(
        MonitoringServiceInterface $monitoringService,
        MonitoringToUtilNetworkServiceInterface $utilNetworkService
    ) {
        $this->monitoringService = $monitoringService;
        $this->utilNetworkService = $utilNetworkService;
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
        return static::TRANSACTION_NAME_PREFIX . $event->getCommand()->getName();
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
