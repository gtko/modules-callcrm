<?php

namespace Modules\CallCRM\Http\Livewire;

use Livewire\Component;

class AppelStatusJoint extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     *
     */
    public $joint;
    public $appelId;

    protected $listeners = ['jointRefresh' => '$refresh'];

    public function mount(\Modules\CallCRM\Models\Appel $appel)
    {
        $this->appelId = $appel->id;
    }

    public function render()
    {
        $appel = \Modules\CallCRM\Models\Appel::find($this->appelId);
        $this->joint = $appel->joint;

        return view('callcrm::livewire.appel-status-joint');
    }
}
