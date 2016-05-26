<?php
namespace Discutea\DForumBundle\Tests\Controller;

use Discutea\DForumBundle\Tests\TestBase;


/**
 * AdminControllerTest
 * 
 * Simple test for permissions routes admin
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class AdminControllerTest extends TestBase
{
    public function testIndexAction()
    {
        $url = $this->client->getContainer()->get('router')->generate('discutea_forum_admin_dashboard');
        $this->tryUrlModerator($url);
    }
}
