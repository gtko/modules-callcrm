<?php

namespace Modules\CallCRM\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\BaseCore\Contracts\Entities\UserEntity;
use Modules\CoreCRM\Models\Dossier;
use Modules\SearchCRM\Entities\SearchResult;
use Modules\SearchCRM\Interfaces\SearchableModel;
use Modules\TaskCalendarCRM\Interfaces\TaskableModel;

/**
 * Class Appels
 * @property int $id
 * @property  int $dossier_id
 * @property  int $user_id
 * @property  int $caller_id
 * @property \Modules\BaseCore\Contracts\Entities\UserEntity $user
 * @property \Modules\CoreCRM\Models\Dossier $dossier
 * @property boolean $joint
 * @property boolean $important
 * @property Carbon $date
 * @mixin Builder
 * @mixin \Illuminate\Database\Query\Builder
 */
class Appel extends Model implements SearchableModel, TaskableModel
{
    use HasFactory;

    protected $dates = ['date'];

    protected $fillable = ['dossier_id', 'user_id', 'caller_id', 'note', 'joint', 'important', 'date'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(app(UserEntity::class)::class);
    }

    public function caller(): BelongsTo
    {
        return $this->belongsTo(app(UserEntity::class)::class);
    }

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }


    public function getSearchResult(): SearchResult
    {
       $title =  "Appel pour le {$this->date->format('d-m-Y H:i')} ";

       $result = new SearchResult(
           $this,
           $title,
           route('dossiers.show', [$this->dossier->client, $this->dossier]),
           'Appels',
           html: "<small>{$this->dossier->client->format_name}</small> - <small class='".(($this->joint)?'text-green-500':'text-red-500')."'>".(($this->joint)?'Joint':'Non-joint')."</small>"
       );
       $result->setSvg('phone');
       return $result;
    }

    public function checkHandle($optionsChecked = null)
    {
        if($optionsChecked === 'join'){
            $this->joint = 1;
        }

        if($optionsChecked === 'non-join'){
            $this->joint = 0;
        }

        $this->save();
    }

    public function canCheck(): bool
    {
        return true;
    }

    public function actions(): array
    {
        return [
            'join' => "Joint",
            'non-join' => 'Non-joint',
        ];
    }
}
