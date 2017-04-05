<?php
namespace Tests\TestCase\ProjectAppBundle\Controller;

use Tests\TestCase\BaseTestCase;

/**
 * Class DefaultControllerTest
 *
 * @package Tests\TestCase\ProjectAppBundle\Controller
 */
class DefaultControllerTest extends BaseTestCase
{
    public function testIndexAction()
    {
        $client = $this->getClient();
        $client->request('GET', $this->generateUrl('homepage'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}