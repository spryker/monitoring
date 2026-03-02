<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Business\MonitoringTransactionNamingStrategy;

use Generated\Shared\Transfer\MonitoringTransactionEventTransfer;

class FirstArgumentMonitoringConsoleTransactionNamingStrategy implements MonitoringTransactionNamingStrategyInterface
{
    /**
     * @var array<string>
     */
    protected array $commandNames = [];

    /**
     * @param array<string> $commandNames
     */
    public function __construct(array $commandNames)
    {
        $this->commandNames = $commandNames;
    }

    public function isApplicable(
        MonitoringTransactionEventTransfer $monitoringTransactionEventTransfer
    ): bool {
        return in_array($monitoringTransactionEventTransfer->getCommandName(), $this->commandNames, true);
    }

    public function getMonitoringTransactionName(
        MonitoringTransactionEventTransfer $monitoringTransactionEventTransfer
    ): ?string {
        $arguments = array_values($monitoringTransactionEventTransfer->getArguments());
        $commandName = $monitoringTransactionEventTransfer->getCommandName();
        $transactionNamePrefix = $monitoringTransactionEventTransfer->getTransactionNamePrefix();
        $argumentValue = $arguments[1] ?? '';

        return implode(' ', [$transactionNamePrefix, $commandName, $argumentValue]);
    }
}
