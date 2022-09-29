<?php


namespace Modules\CallCRM\Contracts\Repositories;


use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\BaseCore\Contracts\Entities\UserEntity;
use Modules\BaseCore\Interfaces\RepositoryFetchable;
use Modules\CallCRM\Models\Appel;
use Modules\CoreCRM\Models\Dossier;
use Modules\SearchCRM\Interfaces\SearchableRepository;

interface AppelRepositoryContract extends SearchableRepository, RepositoryFetchable
{
    /**
     * @param int $caller_id
     * @param \Illuminate\Contracts\Auth\Authenticatable|\Modules\BaseCore\Contracts\Entities\UserEntity $user
     * @param \Modules\CoreCRM\Models\Dossier $dossier
     * @param Carbon $date
     * @param bool $important
     * @param bool $joint
     * @param string $note
     * @return \Modules\CallCRM\Models\Appel
     */

public function createAppel(int $caller_id, Authenticatable|UserEntity $user, Dossier $dossier, Carbon $date, bool $important, bool $joint, string $note = ''):Appel;
}
