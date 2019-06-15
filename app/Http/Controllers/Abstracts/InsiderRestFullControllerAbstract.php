<?php

namespace App\Http\Controllers\Abstracts;

use Illuminate\Http\Request;
use App\Http\Controllers\InsiderBaseController;

abstract class InsiderRestFullControllerAbstract extends InsiderBaseController{

    /** @var Model */
    private $Service;

    /**
     * RETURN ALL MODEL DATA
     *
     * @return mixed
     */
    public function getAll(){}

    /**
     * RETURN MODEL BY ID
     *
     * @param int $id
     *
     * @return mixed
     */
    public function get($id){}

    /**
     * CREATE NEW MODEL
     *
     * @param Request $request
     * @return mixed
     */
    public function set(Request $request){}

    /**
     * UBPDATE MODEL BY ID
     *
     * @param Request $request
     * @param int $id
     *
     * @return mixed
     */
    public function update(Request $request , $id){}

    /**
     * SOFT DELETE MODEL BY ID
     *
     * @param int $id
     *
     * @return mixed
     */
    public function delete($id){}

}