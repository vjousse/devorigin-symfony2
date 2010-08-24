<?php

namespace Symfony\Framework\Bundle;

use Symfony\Component\DependencyInjection\ContainerInterface;

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * BundleInterface.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
interface BundleInterface
{
    /**
     * Boots the Bundle.
     */
    public function boot();

    /**
     * Shutdowns the Bundle.
     */
    public function shutdown();

    /**
     * Sets the Container associated with this bundle.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container);
}
