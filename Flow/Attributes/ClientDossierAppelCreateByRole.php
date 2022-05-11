<?php


namespace Modules\CallCRM\Flow\Attributes;


use Illuminate\Contracts\Auth\Authenticatable;
use Modules\BaseCore\Contracts\Entities\UserEntity;
use Modules\BaseCore\Contracts\Repositories\RoleRepositoryContract;
use Modules\CallCRM\Models\Appel;
use Modules\CoreCRM\Flow\Attributes\Attributes;
use Modules\CoreCRM\Flow\Interfaces\FlowAttributes;
use Modules\CoreCRM\Models\User;
use Spatie\Permission\Models\Role;

class ClientDossierAppelCreateByRole extends Attributes
{

    public function __construct
    (
        protected Role $role,
        protected UserEntity $user,
        protected Appel $appel
    )
    {
        parent::__construct();
    }

    public function getType(): string
    {
        return static::TYPE_CALL;
    }

    public function componentCacheable():bool{
        return false;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user->id,
            'appel_id' => $this->appel->id,
            'role_id' => $this->role->id
        ];
    }

    public function getUser():UserEntity
    {
        return $this->user;
    }

    public function getAppel():Appel
    {
        return $this->appel;
    }

    public function getRole():Role
    {
        return $this->role;
    }

    public static function instance(array $value): FlowAttributes
    {
        $user = app(UserEntity::class)::find($value['user_id']);
        $appel = Appel::find($value['appel_id']);
        $role = Role::find($value['role_id']);

        return new ClientDossierAppelCreateByRole($role, $user, $appel);
    }
}
