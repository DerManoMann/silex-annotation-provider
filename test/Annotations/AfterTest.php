<?php
/**
 * This file is part of the silex-annotation-provider package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license       MIT License
 * @copyright (c) 2014, Dana Desrosiers <dana.desrosiers@gmail.com>
 */

namespace DDesrosiers\Test\SilexAnnotations\Annotations;

use DDesrosiers\SilexAnnotations\Annotations as SLX;
use DDesrosiers\SilexAnnotations\AnnotationServiceProvider;
use Exception;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class AfterTest extends \PHPUnit_Framework_TestCase
{
    /** @var Application */
    protected $app;

    /** @var Client */
    protected $client;

    public function setUp()
    {
        $this->app = new Application();
        $this->app['debug'] = true;

        $this->app->register(
                  new AnnotationServiceProvider(),
                  array(
                      "annot.controllers" => array("DDesrosiers\\Test\\SilexAnnotations\\Annotations\\AfterTestController")
                  )
        );

        $this->client = new Client($this->app);
    }

    public function testAfter()
    {
        $this->client->request("GET", "/test");
        $response = $this->client->getResponse();
        $this->assertEquals(500, $response->getStatusCode());
    }
}

class AfterTestController
{
    /**
     * @SLX\Request(method="GET", uri="/test")
     * @SLX\After("DDesrosiers\SilexAnnotations\Test\Annotations\AfterTestController::afterCallback")
     */
    public function testMethod($var)
    {
        return new Response($var);
    }

    public static function afterCallback()
    {
        throw new Exception("after callback");
    }
}