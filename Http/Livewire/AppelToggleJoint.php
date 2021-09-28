<?php

namespace Modules\CallCRM\Http\Livewire;

use Livewire\Component;

class AppelToggleJoint extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public $appel;

    public function mount($appelId)
    {
        $this->appel = \Modules\CallCRM\Models\Appel::find($appelId);

    }

    public function toogle($status)
    {

        $this->appel->joint = $status;
        $this->appel->save();
        $this->emit('jointRefresh');
    }

    public function render()
    {
        return view('callcrm::livewire.appel-toggle-joint');
    }
}
