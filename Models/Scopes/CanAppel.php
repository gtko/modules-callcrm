<?php

namespace Modules\CallCRM\Models\Scopes;


use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\CallCRM\Models\Appel;

trait CanAppel
{

    public function appels():HasMany
    {
        return $this->hasMany(Appel::class);
    }


}
