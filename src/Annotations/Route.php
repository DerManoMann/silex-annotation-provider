<?php
/**
 * This file is part of the silex-annotation-provider package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license       MIT License
 * @copyright (c) 2014, Dana Desrosiers <dana.desrosiers@gmail.com>
 */

namespace DDesrosiers\SilexAnnotations\Annotations;

use Silex\Application;
use Silex\ControllerCollection;

/**
 * @Annotation
 * @Target("METHOD")
 * @author Dana Desrosiers <dana.desrosiers@gmail.com>
 */
class Route
{
    /** @var Request[] */
    public $request;

    /** @var Convert[] */
    public $convert;

    /** @var Assert[] */
    public $assert;

    /** @var RequireHttp[] */
    public $requireHttp;

    /** @var RequireHttps[] */
    public $requireHttps;

    /** @var Value[] */
    public $value;

    /** @var Host[] */
    public $host;

    /** @var Before[] */
    public $before;

    /** @var After[] */
    public $after;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $annotations = is_array($values['value']) ? $values['value'] : array($values['value']);
        foreach ($annotations as $annotation) {
            $classPath = explode("\\", get_class($annotation));
            $propertyName = lcfirst(array_pop($classPath));
            $this->{$propertyName}[] = $annotation;
        }
    }

    /**
     * Process annotations on a method to register it as a controller.
     *
     * @param \Silex\ControllerCollection $controllerCollection the controller collection to add the route to
     * @param string                      $controllerName       fully qualified method name of the controller
     * @param \Silex\Aplication           $app                  the application
     */
    public function process(ControllerCollection $controllerCollection, $controllerName, Application $app)
    {
        foreach ($this->request as $request) {
            $controller = $request->process($controllerCollection, $controllerName);
            foreach ($this as $annotations) {
                if (is_array($annotations)) {
                    foreach ($annotations as $annotation) {
                        if ($annotation instanceof RouteAnnotation) {
                            $annotation->process($controller);
                        }
                    }
                }
            }
        }
    }
}
