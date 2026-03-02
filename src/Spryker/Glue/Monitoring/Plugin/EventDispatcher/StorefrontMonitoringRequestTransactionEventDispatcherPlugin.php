<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Monitoring\Plugin\EventDispatcher;

/**
 * @method \Spryker\Glue\Monitoring\MonitoringFactory getFactory()
 */
class StorefrontMonitoringRequestTransactionEventDispatcherPlugin extends AbstractMonitoringRequestTransactionEventDispatcherPlugin
{
    protected function getLocaleName(): string
    {
        return $this->getFactory()->getLocaleClient()->getCurrentLocale();
    }
}
