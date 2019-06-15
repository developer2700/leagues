<?php
 

namespace App\Repository\Interfaces;

interface RepositoryMatchInterface extends RepositoryInterface
{
    /**
     * CHECK ALL WEEK MATCHES IS END
     *
     * @param $iId
     *
     * @return bool
     */
    public function checkWeekIsEnd($iId): bool;
}
