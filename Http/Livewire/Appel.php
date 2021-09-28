<?php

namespace Modules\CallCRM\Http\Livewire;

use Livewire\Component;
use Modules\BaseCore\Actions\Dates\DateStringToCarbon;
use Modules\BaseCore\Contracts\Entities\UserEntity;
use Modules\CallCRM\Contracts\Repositories\AppelRepositoryContract;
use Modules\CallCRM\Flow\Attributes\ClientDossierAppelCreate;
use Modules\CoreCRM\Models\Client;
use Modules\CoreCRM\Models\Dossier;
use Modules\CoreCRM\Services\FlowCRM;
use Modules\TaskCalendarCRM\Contracts\Repositories\TaskRepositoryContract;

class Appel extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */

    public $appel = [];
    public $joint = false;
    public $important = false;
    public $callers;
    public $caller_id;
    public $client_id;
    public $dossier;

    protected $rules = [
        'appel.note' => '',
        'appel.date' => 'required',
        'joint' => 'required',
        'important' => '',
    ];

    public function mount($dossierId, $clientId)
    {
        $this->client_id = $clientId;


        $this->callers = app(UserEntity::class)::with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'commercial');

            })
            ->get();
        $this->dossier = Dossier::find($dossierId);


        //@todo cree le reposotory a faire

    }

    public function store()
    {
        $this->validate();

        $date = (new DateStringToCarbon())->handle($this->appel['date']);

        $appel = app(AppelRepositoryContract::class)
            ->createAppel($this->caller_id, \Auth::user(), $this->dossier, $date, $this->important, $this->joint, $this->appel['note'] ?? '');

        $caller = app(UserEntity::class)::find($this->caller_id);
        $client = Client::find($this->client_id);

        $message ='Appel du client ' . ' ' . $client->format_name;
        $url = '/clients/' . $client->id . '/dossiers' . $this->dossier->id;

        app(TaskRepositoryContract::class)->createTask($caller, $date, 'Appel' , $message, $url);
        (new FlowCRM())->add($this->dossier,new ClientDossierAppelCreate(\Auth::user(), $appel));


        return redirect()->to('/clients/' . $this->client_id . '/dossiers/' . $this->dossier->id);

    }

    public function toggleJoint($joint)
    {
        $this->joint = $joint;
    }

    public function toggleImportant()
    {
        if ($this->important) {
            $this->important = false;
        } else {
            $this->important = true;
        }
    }

    public function render()
    {
        return view('callcrm::livewire.appel');
    }
}
