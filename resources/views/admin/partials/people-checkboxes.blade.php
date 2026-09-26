<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-bold text-gray-800 mb-4">چهره‌ها و شهدا (مرتبط)</h3>
    @if(($people ?? collect())->isEmpty())
        <p class="text-xs text-gray-400">
            هنوز چهره‌ای ثبت نشده؛
            <a href="{{ route('admin.people.create') }}" class="text-primary">از اینجا اضافه کنید</a>.
        </p>
    @else
        <div class="max-h-64 overflow-y-auto space-y-1 pr-1">
            @foreach($people as $person)
                <label class="flex items-center gap-2 py-1.5 cursor-pointer hover:bg-gray-50 rounded-lg px-2">
                    <input type="checkbox" name="people_ids[]" value="{{ $person->id }}"
                        {{ in_array($person->id, old('people_ids', ($selectedPeople ?? collect())->pluck('id')->all())) ? 'checked' : '' }}
                        class="w-4 h-4 text-primary rounded border-gray-300">
                    <span class="text-sm text-gray-700">{{ $person->name }}</span>
                    @if($person->is_martyr)
                        <span class="text-[10px] bg-green-600 text-white px-1.5 py-0.5 rounded-full flex-shrink-0">شهید</span>
                    @endif
                </label>
            @endforeach
        </div>
    @endif
</div>