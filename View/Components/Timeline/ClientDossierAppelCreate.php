<?php


namespace Modules\CallCRM\View\Components\Timeline;


use Illuminate\View\View;
use Modules\CoreCRM\View\Components\Timeline\TimelineComponent;

class ClientDossierAppelCreate extends TimelineComponent
{

    function render(): View
    {
        return view('callcrm::components.timeline.client.dossier.appel.create');
    }
}
