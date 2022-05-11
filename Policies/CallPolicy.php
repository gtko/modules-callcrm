<?php

namespace Modules\CallCRM\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\BaseCore\Contracts\Entities\UserEntity;
use Modules\CoreCRM\Contracts\Entities\ClientEntity;

class CallPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the client can views any models.
     *
     * @param \Modules\BaseCore\Contracts\Entities\UserEntity $user
     * @return bool
     */
    public function viewAny(UserEntity $user)
    {
        return $user->hasPermissionTo('list appels');
    }

    /**
     * Determine whether the client can views the model.
     *
     * @param \Modules\BaseCore\Contracts\Entities\UserEntity $user
     * @return bool
     */
    public function view(UserEntity $user)
    {
        return $user->hasPermissionTo('views appels');
    }

    /**
     * Determine whether the client can create models.
     *
     * @param \Modules\BaseCore\Contracts\Entities\UserEntity $user
     * @return bool
     */
    public function create(UserEntity $user)
    {
        return $user->hasPermissionTo('create appels');
    }
}
