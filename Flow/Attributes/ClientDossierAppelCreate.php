<?php


namespace Modules\CallCRM\Flow\Attributes;


use Illuminate\Contracts\Auth\Authenticatable;
use Modules\BaseCore\Contracts\Entities\UserEntity;
use Modules\CallCRM\Models\Appel;
use Modules\CoreCRM\Flow\Attributes\Attributes;
use Modules\CoreCRM\Flow\Interfaces\FlowAttributes;

class ClientDossierAppelCreate extends Attributes
{

    public function __construct
    (
        protected UserEntity|Authenticatable $user,
        protected Appel $appel
    )
    {
        parent::__construct();
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user->id,
            'appel_id' => $this->appel->id
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

    public static function instance(array $value): FlowAttributes
    {
        $user = app(UserEntity::class)::find($value['user_id']);
        $appel = Appel::find($value['appel_id']);

        return new ClientDossierAppelCreate($user, $appel);
    }
}
