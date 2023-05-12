<?php

/**
 * This file is a part of the SHU package.
 * SHU : Smart Home Ultimate
 * © Claude Migne <monkey3d@wanadoo.fr>
 * 
 * file : User.php - date : 20 janv. 2023
 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 

namespace App\Services\UserManagement;

use App\Entity\TrainingManagement\TrainingUser;
use App\Entity\User;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Security\Core\Security;

class UserSettings
{
    private $doctrine;
    private $security;
    
    public function __construct(
        ManagerRegistry $doctrine,
        Security $security
        ) {
            $this->doctrine = $doctrine;
            $this->security = $security;
    }
    //-------------------------------------------------------------------------------------------------------------
    public function loginCount(): int
    {
        $user = $this->security->getUser();
        $userBDD = $this->doctrine->getRepository(User::class)->findOneBy([
            'uid' => $user->getEntry()->getAttributes()['uid'][0]
        ]);
        
        return $userBDD->getLoginCount();
    }
    //-------------------------------------------------------------------------------------------------------------
    public function plannedTrainingCount(): int
    {
        $user = $this->security->getUser();
        $userBDD = $this->doctrine->getRepository(User::class)->findOneBy([
            'uid' => $user->getEntry()->getAttributes()['uid'][0]
        ]);
        $plannedTraining = $this->doctrine->getRepository(TrainingUser::class)->findBy([
            'user' => $userBDD
        ]);
        return count($plannedTraining);
    }
    //-------------------------------------------------------------------------------------------------------------
}