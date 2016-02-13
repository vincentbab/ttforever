<?php

namespace Mping\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FavoriteRepository extends EntityRepository
{

    public function findOneByUser($user, $type, $name)
    {
        return $this->findOneBy(array('user' => $user, 'type' => $type, 'name' => $name));
    }
}
