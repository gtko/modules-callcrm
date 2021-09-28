<div>
    <form method="POST" wire:submit.prevent="store" class="mt-5">
        <div>
            <div class="grid grid-cols-12 gap-2 mb-2">
                <x-basecore::inputs.datetime
                    name="date"
                    class="col-span-6 mt-2 form-control form-control-sm"
                    placeholder="Date"
                    wire:model="appel.date"
                >
                </x-basecore::inputs.datetime>
                <x-basecore::inputs.text
                    name="note"
                    class="col-span-6 mt-2 form-control form-control-sm"
                    placeholder="Ajouter une note pour cet appel"
                    wire:model="appel.note"
                >
                </x-basecore::inputs.text>
            </div>
            <x-basecore::inputs.select name="callers_id" class="form-control form-control-sm" required="required"
                             wire:model="caller_id">
                <option selected value="">Choisissez un commercial</option>
                @foreach($callers as $caller)
                    <option
                        value="{{ $caller->id}}">{{ $caller->formatName }}</option>
                @endforeach
            </x-basecore::inputs.select>
        </div>
        <div class="flex justify-between mt-4" >
            <div x-data="{ joint: false, important: false }">
            <span class="btn-sm rounded text-white w-48 my-4" :class="[joint ? 'bg-gray-600' : 'bg-red-600']"
                  @click="joint = false" wire:click="toggleJoint(false)">Non-joint</span>
                <span class="btn-sm rounded text-white w-48 my-4" :class="[joint ? 'bg-green-600' : 'bg-gray-600']"
                      @click="joint = true" wire:click="toggleJoint(true)">Joint</span>
                <span class="btn-sm rounded text-white w-48 ml-8" :class="[important ? 'bg-red-600' : 'bg-gray-600']"
                      @click="important = !important" wire:click="toggleImportant()">Important</span>

            </div>
            <button type="submit" class="btn-sm rounded text-white w-48 bg-blue-600">Save</button>
        </div>
    </form>
</div>
