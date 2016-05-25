<?php
namespace Discutea\DForumBundle\Tests\Controller;

use Discutea\DForumBundle\Tests\TestBase;
use Symfony\Component\HttpFoundation\Response;

class RoutesTest extends TestBase
{
    public function testRoutes() {
       $crawler = $this->client->request('GET', '/forum/');
   //    var_dump($this->client->getResponse()->isSuccessful());
   //    $this->assertTrue($this->client->getResponse()->isSuccessful());
       $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
       
       $crawler = $this->client->request('GET', '/forum/admin');
       $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
       
       $this->assertEquals('Hello', 'Hello');
    }
}
