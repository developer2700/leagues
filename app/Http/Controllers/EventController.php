<?php
 
namespace App\Http\Controllers;


use Illuminate\Container\EntryNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Abstracts\InsiderRestFullControllerAbstract;
use App\Services\EventService;

class EventController extends InsiderRestFullControllerAbstract
{
    /** @var EventService */
    private $Service;

    public function __construct(EventService $service)
    {
        $this->Service = $service;
    }

    public function getAll()
    {
        return response()->json($this->Service->getAll(), 200);

    }

    public function get($id)
    {
        try {
            return response()->json($this->Service->get($id), 200);
        } catch (EntryNotFoundException $e) {
            return response()->json('Model Not Fund', '404');
        }
    }

    public function set(Request $request)
    {
        $param = $request->all();
        try {
            return response()->json($this->Service->set($param), 200);
        } catch (ValidationException $e) {
            return response()->json('Bad Request', '400');
        } catch (EntryNotFoundException $e) {
            return response()->json('Model Not Fund', '404');
        }
    }

    public function update(Request $request, $id)
    {
        $param = $request->all();

        try {
            return response()->json($this->Service->update($param, $id), 200);
        } catch (EntryNotFoundException $e) {
            return response()->json('Model Not Fund', '404');
        } catch (ValidationException $e) {
            return response()->json('Bad Request', '400');
        }
    }

    public function delete($id)
    {
        try {
            $this->Service->delete($id);
            return response('Success Full', 200);
        } catch (EntryNotFoundException $e) {
            return response()->json('Model Not Fund', '404');
        }
    }
}