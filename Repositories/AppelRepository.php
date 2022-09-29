<?php

namespace Modules\CallCRM\Repositories;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\BaseCore\Contracts\Entities\UserEntity;
use Modules\BaseCore\Helpers\HasInterface;
use Modules\BaseCore\Repositories\AbstractRepository;
use Modules\CallCRM\Contracts\Repositories\AppelRepositoryContract;
use Modules\CallCRM\Models\Appel;
use Modules\CoreCRM\Contracts\Repositories\DossierRepositoryContract;
use Modules\CoreCRM\Models\Dossier;

class AppelRepository extends AbstractRepository implements AppelRepositoryContract
{

    public function createAppel(int $caller_id, UserEntity|Authenticatable $user, Dossier $dossier, Carbon $date, bool $important, bool $joint, string $note = ''):Appel
    {
        $appel = new Appel();
        $appel->note = $note;
        $appel->caller_id = $caller_id;
        $appel->user_id = $user->id;
        $appel->dossier_id = $dossier->id;
        $appel->date = $date;
        $appel->important = $important;
        $appel->joint = $joint;
        $appel->save();

        return $appel;
    }

    public function getModel(): Model
    {
        return new Appel();
    }

    public function searchQuery(Builder $query, string $value, mixed $parent = null): Builder
    {
        if(!HasInterface::has(DossierRepositoryContract::class, $parent)) {
            $query->orWhereHas('dossier', function ($query) use ($value) {
                app(DossierRepositoryContract::class)->searchQuery($query, $value, $this);
            });
        }

        $query = $this->searchDates($query, $value, 'date');

        return $query;
    }
}
