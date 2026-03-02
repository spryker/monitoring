<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring\Business\MonitoringTransactionNamingStrategy;

use Generated\Shared\Transfer\MonitoringTransactionEventTransfer;

interface MonitoringTransactionNamingStrategyInterface
{
    public function isApplicable(MonitoringTransactionEventTransfer $monitoringTransactionEventTransfer): bool;

    public function getMonitoringTransactionName(MonitoringTransactionEventTransfer $monitoringTransactionEventTransfer): ?string;
}
