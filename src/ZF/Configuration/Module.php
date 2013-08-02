<?php
/**
 * @license   http://opensource.org/licenses/BSD-2-Clause BSD-2-Clause
 */

namespace ZF\Configuration;

/**
 * ZF2 module
 */
class Module
{
    /**
     * Retrieve autoloader configuration
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array('Zend\Loader\StandardAutoloader' => array('namespaces' => array(
            __NAMESPACE__ => __DIR__,
        )));
    }

    /**
     * Retrieve module configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array('factories' => array(
            'ZF\Configuration\ConfigResource' => function ($services) {
                $config = array();
                $file   = 'config/autoload/development.php';
                if ($services->has('Config')) {
                    $config = $services->get('Config');
                    if (isset($config['zf-configuration'])
                        && isset($config['zf-configuration']['config-file'])
                    ) {
                        $file = $config['zf-configuration']['config-file'];
                    }
                }

                $writer = $services->get('ZF\Configuration\ConfigWriter');

                return new ConfigResource($config, $file, $writer);
            },
        ));
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'ZF\Configuration\ConfigController' => function ($controllers) {
                $services = $controllers->getServiceLocator();
                return new ConfigController($services->get('ZF\Configuration\ConfigResource'));
            }
        ));
    }
}
