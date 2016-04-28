<?php

namespace Discutea\DForumBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Discutea\DForumBundle\DependencyInjection\ForumExtension;

class DForumBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ForumExtension();
    }
}
