<?php
 
namespace App\Services;


use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use App\Repository\Mysql\RepositorySetting;
use \Illuminate\Support\Facades\Validator;

class SettingService extends BaseService
{

    private $Repository;

    public function __construct(RepositorySetting $repository = null)
    {
        $this->Repository = $repository ?? new RepositorySetting();
    }

    /**
     * GET ALL MODEL DATA
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->Repository->getAll();
    }

    /**
     * GET MODEL DATA BY ID
     *
     * @param int $iId
     *
     * @return Collection
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function get(int $iId): Collection
    {
        return $this->Repository->get($iId);
    }

    /**
     * CREATE NEW MODEL
     *
     * @param array $params
     *
     * @return Collection
     * @throws ValidationException
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function set(array $params): Collection
    {
        $params = $this->validate($params);
        return $this->Repository->set($params);
    }

    /**
     * UPDATE MODEL BY ID
     *
     * @param array $params
     * @param int $iId
     *
     * @return Collection
     * @throws ValidationException
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function update(array $params, int $iId): Collection
    {
        $params = $this->validate($params);
        $params['setting_id'] = $iId;
        return $this->Repository->update($params);
    }

    /**
     * DELETE MODEL
     *
     * @param int $iId
     *
     * @return bool
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function delete(int $iId): bool
    {
        return $this->Repository->delete($iId);
    }

    /**
     * VALIDATE MODEL ATTRIBUTES
     *
     * @param array $params
     *
     * @return array
     * @throws ValidationException
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    private function validate(array $params): array
    {
        $BaseSettingService = new BaseSettingService();

        if (array_key_exists('base_setting_id', $params)) {
            $BaseSettingService->get($params['base_setting_id']);
        }

        $rules = [
            'base_setting_id' => 'required',
            'parent_id' => 'numeric|nullable',
            'title' => 'required',
            'description' => 'nullable'
        ];

        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            return $params;
        }
    }
}