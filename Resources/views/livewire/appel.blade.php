<div>
    <form method="POST" wire:submit.prevent="store" class="mt-5">
        <div>
            <div class="grid grid-cols-12 gap-2 mb-2">
                <x-basecore::inputs.datetime
                    name="date"
                    class="col-span-6 mt-2 form-control form-control-sm"
                    placeholder="Date"
                    wire:model="appel.date"
                    required="require"
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
            <div class="grid grid-cols-12 gap-2 mb-2">
                <x-basecore::inputs.select name="callers_id" class="form-control form-control-sm col-span-6"
                                           wire:model="caller_id">
                    <option value="">Choisissez un commercial</option>
                    @foreach($callers as $caller)
                        <option value="{{ $caller->id}}">{{ $caller->formatName }}</option>
                    @endforeach
                </x-basecore::inputs.select>

                <x-basecore::inputs.select name="role_id" class="form-control form-control-sm col-span-6"
                                           wire:model="role_id">
                    <option selected value="">Choisissez un role</option>
                    @foreach($roles as $role)
                        <option
                            value="{{ $role->id}}">{{ $role->name }}</option>
                    @endforeach
                </x-basecore::inputs.select>

                <x-basecore::inputs.select name="type" class="form-control form-control-sm col-span-6"
                                           wire:model="type">
                    <option value="normal">Normal</option>
                    <option value="appel">Appel</option>
                </x-basecore::inputs.select>

            </div>
        </div>
        <div class="flex justify-between mt-4">
            <div x-data="{ joint: false, important: false }">
                <span class="btn-sm rounded text-white w-48" :class="[important ? 'bg-red-600' : 'bg-gray-600']"
                      @click="important = !important" wire:click="toggleImportant()">Important</span>
            </div>
            <button type="submit" class="btn-sm rounded text-white w-48 bg-blue-600">Save</button>
        </div>
    </form>
</div>
