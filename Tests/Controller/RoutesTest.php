<?php
namespace Discutea\DForumBundle\Tests\Controller;

use Discutea\DForumBundle\Tests\TestBase;
use Symfony\Component\HttpFoundation\Response;

class RoutesTest extends TestBase
{
    public function testRoutes() {
     
        
        
        $this->tryClientRoutes();
        $this->tryAdminRoutes();
    }

    private function tryClientRoutes() {
        
        $this->client->request('GET', '/forum/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
       
        $this->client->request('GET', '/forum/admin');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        
        return $this;
    }

    private function tryAdminRoutes() {
        $this->client = $this->doLogin('admin', 'password');
        
        $this->client->request('GET', '/forum/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
       
        $this->client->request('GET', '/forum/admin');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        
        return $this;
    }
}
