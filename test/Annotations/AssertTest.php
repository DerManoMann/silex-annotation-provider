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
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class AssertTest extends \PHPUnit_Framework_TestCase
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
                      "annot.controllers" => array("DDesrosiers\\Test\\SilexAnnotations\\Annotations\\AssertTestController")
                  )
        );

        $this->client = new Client($this->app);
    }

    public function testAssert()
    {
        $this->client->request("GET", "/test/45");
        $response = $this->client->getResponse();
        $this->assertEquals('200', $response->getStatusCode());

        $this->client->request("GET", "/test/fail");
        $response = $this->client->getResponse();
        $this->assertEquals('404', $response->getStatusCode());
    }
}

class AssertTestController
{
    /**
     * @SLX\Request(method="GET", uri="test/{var}")
     * @SLX\Assert(variable="var", regex="\d+")
     */
    public function testMethod($var)
    {
        return new Response($var);
    }
}