<?php

namespace Modules\CallCRM\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\BaseCore\Actions\Dates\DateStringToCarbon;
use Modules\BaseCore\Contracts\Entities\UserEntity;
use Modules\BaseCore\Contracts\Repositories\RoleRepositoryContract;
use Modules\BaseCore\Contracts\Repositories\UserRepositoryContract;
use Modules\CallCRM\Contracts\Repositories\AppelRepositoryContract;
use Modules\CallCRM\Flow\Attributes\ClientDossierAppelCreate;
use Modules\CallCRM\Flow\Attributes\ClientDossierAppelCreateByRole;
use Modules\CoreCRM\Contracts\Services\FlowContract;
use Modules\CoreCRM\Models\Client;
use Modules\CoreCRM\Models\Dossier;
use Modules\CoreCRM\Services\FlowCRM;
use Modules\CrmAutoCar\Flow\Attributes\ClientDossierRappeler;
use Modules\TaskCalendarCRM\Contracts\Repositories\TaskRepositoryContract;
use Modules\TaskCalendarCRM\Flow\Attributes\AddTaskCreate;

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
    public $role_id;
    public $type;


    protected $rules = [
        'appel.note' => '',
        'appel.date' => 'required',
        'joint' => 'required',
        'important' => '',
        'type' => '',
    ];

    public function mount($dossierId, $clientId)
    {
        $this->client_id = $clientId;
        $this->caller_id = \Auth::user()->id;

        $this->type = 'normal';

        $this->callers = app(UserRepositoryContract::class)->newQuery()
            ->whereDoesntHave('roles', function($query){
                $query->where('name', 'fournisseur');
            })
            ->get();
        $this->dossier = Dossier::find($dossierId);
    }

    public function store()
    {
        $this->validate();

        $date = (new DateStringToCarbon())->handle($this->appel['date']);

        $caller = app(UserEntity::class)::find($this->caller_id);
        $client = Client::find($this->client_id);

        $message = $this->appel['note'] ?? 'Appel du client ' . ' ' . $client->format_name;
        $url = route('dossiers.show', [$client, $this->dossier]);

        $data = [
            'type' => $this->type,
            'important' => $this->important,
        ];

        if($this->type === 'appel'){
            $data['joint'] = null;
        }

        if ($this->role_id && $this->role_id > 0) {
            $role = app(RoleRepositoryContract::class)->fetchById($this->role_id);
            $data['uuid'] = uniqid('task_', true);
            $data['role_name'] = $role->name;
            foreach ($role->users as $user) {

//                $appel = app(AppelRepositoryContract::class)
//                    ->createAppel($user->id, \Auth::user(), $this->dossier, $date, $this->important, $this->joint, $this->appel['note'] ?? '');
                $task = app(TaskRepositoryContract::class)
                    ->createTask($user, $date, 'Rappel', $message, $url,0,"#7413cf", $this->dossier, $data);


            }

            (new FlowCRM())->add($this->dossier, new AddTaskCreate($task));
        }

        if($this->caller_id)
        {
//            $appel = app(AppelRepositoryContract::class)
//                ->createAppel($this->caller_id, \Auth::user(), $this->dossier, $date, $this->important, $this->joint, $this->appel['note'] ?? '');

            $task = app(TaskRepositoryContract::class)
                ->createTask($caller, $date, 'Rappel', $message, $url,0,"#1969bf", $this->dossier, $data);
            (new FlowCRM())->add($this->dossier, new AddTaskCreate($task));
            app(FlowContract::class)
                ->add($this->dossier, new ClientDossierRappeler($this->dossier, $this->dossier->commercial, Auth::user()));
//            (new FlowCRM())->add($this->dossier, new ClientDossierAppelCreate(\Auth::user(), $appel));
        }

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
        $roles = app(RoleRepositoryContract::class)->fetchAll();

        return view('callcrm::livewire.appel',
            [
                'roles' => $roles
            ]);
    }
}
