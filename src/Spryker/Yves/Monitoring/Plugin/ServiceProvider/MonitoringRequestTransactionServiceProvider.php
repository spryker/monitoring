<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @deprecated Use {@link \Spryker\Yves\Monitoring\Plugin\EventDispatcher\MonitoringRequestTransactionEventDispatcherPlugin} instead.
 *
 * @method \Spryker\Yves\Monitoring\MonitoringFactory getFactory()
 */
class MonitoringRequestTransactionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public function register(Application $app): void
    {
    }

    public function boot(Application $app): void
    {
        $this->addControllerListener($app);
    }

    protected function addControllerListener(Application $app): void
    {
        $this->getDispatcher($app)->addSubscriber(
            $this->getFactory()->createControllerListener(),
        );
    }

    protected function getDispatcher(Application $app): EventDispatcherInterface
    {
        return $app['dispatcher'];
    }
}
