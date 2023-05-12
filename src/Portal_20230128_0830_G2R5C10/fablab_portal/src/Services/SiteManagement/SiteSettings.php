<?php

/**
 * This file is a part of the SHU package.
 * SHU : Smart Home Ultimate
 * © Claude Migne <monkey3d@wanadoo.fr>
 * 
 * file : Setting.php - date : 7 janv. 2023
 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 

namespace App\Services\SiteManagement;

use App\Entity\SiteManagement\Setting;

use Doctrine\Persistence\ManagerRegistry;

class SiteSettings
{
    public function __construct(
        ManagerRegistry $doctrine
        ) {
            $this->doctrine = $doctrine;
    }
    
    public function isOpen(): bool
    {
        $id = 1;
        $setting = $this->doctrine->getRepository(Setting::class)->find($id);
        return $setting->isOpeningStatus();
    }
}
