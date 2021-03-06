<?php 

namespace Sijot\Repositories;

use ActivismeBE\DatabaseLayering\Repositories\Contracts\RepositoryInterface;
use ActivismeBE\DatabaseLayering\Repositories\Eloquent\Repository;
use Sijot\Permission;

/**
 * Class PermissionRepository
 *
 * @package Sijot\Repositories
 */
class PermissionRepository extends Repository
{
    /**
     * Set the eloquent model class for the repository.
     *
     * @return string
     */
    public function model()
    {
        return Permission::class;
    }
}