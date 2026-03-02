<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring\EventHandler;

use Symfony\Component\Console\Event\ConsoleTerminateEvent;

interface EventHandlerInterface
{
    public function handleConsoleTerminateEvent(ConsoleTerminateEvent $event): void;
}
