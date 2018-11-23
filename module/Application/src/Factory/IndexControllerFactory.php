<?php
/**
 * @link      ZF3.Prototype.Project
 * @copyright Copyright (c) 2000-2018 The PHOENIX Developer Studio (http://simon-phoenix.se)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Factory;

use Application\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\Renderer\RendererInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $tcpdf = $container->get(\TCPDF::class);
        $renderer = $container->get(RendererInterface::class);
        return new IndexController(
            $tcpdf,
            $renderer
        );
    }
}