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

use Silex\Controller;

/**
 * @Annotation
 * @Target({"METHOD", "ANNOTATION"})
 */
class Assert implements RouteAnnotation
{
    /** @var string */
    public $variable;

    /** @var string */
    public $regex;

    /**
     * @param Controller $route
     */
    public function process(Controller $route)
    {
        $route->assert($this->variable, $this->regex);
    }
}
