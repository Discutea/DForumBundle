<?php
namespace Discutea\DForumBundle\Tests\tests\src\Entity;

use FOS\UserBundle\Model\User as BaseUser;

class Users extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
