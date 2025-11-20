<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Monitoring\Plugin\EventDispatcher;

/**
 * @deprecated Depends on context use:
 * - \Spryker\Glue\Monitoring\Plugin\EventDispatcher\BackendMonitoringRequestTransactionEventDispatcherPlugin for Backend application
 * - \Spryker\Glue\Monitoring\Plugin\EventDispatcher\StorefrontMonitoringRequestTransactionEventDispatcherPlugin for Frontend application
 *
 * @method \Spryker\Glue\Monitoring\MonitoringFactory getFactory()
 */
class MonitoringRequestTransactionEventDispatcherPlugin extends AbstractMonitoringRequestTransactionEventDispatcherPlugin
{
    /**
     * @return string
     */
    protected function getLocaleName(): string
    {
        if (APPLICATION === 'GLUE_BACKEND') {
            return $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName();
        }

        return $this->getFactory()->getLocaleClient()->getCurrentLocale();
    }
}
