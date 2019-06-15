<?php

namespace App\Repository\Interfaces;

use Illuminate\Support\Collection;

 
interface RepositoryInterface
{

    /**
     * GET ALL MODEL
     *
     * @return array
     */
    public function getAll():array ;

    /**
     * GET MODEL BY ID
     *
     * @param int $iId
     *
     * @return Collection
     */
    public function get(int $iId):Collection ;

    /**
     * GET MODEL BY ID
     *
     * @param int $iId
     *
     * @return mixed
     */
    public function getModel(int $iId);

    /**
     * CREATE NEW MODEL
     *
     * @param array $params
     *
     * @return Collection
     */
    public function set(array $params):Collection ;

    /**
     * UPDATE MODEL
     *
     * @param array $params
     *
     * @return Collection
     */
    public function update(array $params):collection;

    /**
     * DELETE MODEL
     *
     * @param int $iId
     *
     * @return bool
     */
    public function delete(int $iId):bool ;

}
