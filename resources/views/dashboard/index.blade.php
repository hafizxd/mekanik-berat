<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Alat Berat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-800">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">

                                    @php $count = 1; @endphp

                                    <table class="min-w-full text-left text-sm font-light">
                                        <thead class="border-b font-medium dark:border-neutral-500">
                                            <tr>
                                                <th scope="col" class="px-6 py-4">#</th>
                                                <th scope="col" class="px-6 py-4">Jenis</th>
                                                <th scope="col" class="px-6 py-4">Type</th>
                                                <th scope="col" class="px-6 py-4">Hours Meter</th>
                                                <th scope="col" class="px-6 py-4">Kapasitas</th>
                                                <th scope="col" class="px-6 py-4">Engine</th>
                                                <th scope="col" class="px-6 py-4">Lifting Height</th>
                                                <th scope="col" class="px-6 py-4">Load Center</th>
                                                <th scope="col" class="px-6 py-4">Status</th>
                                                <th scope="col" class="px-6 py-4">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $key => $item)
                                                <tr class="border-b transition duration-300 ease-in-out hover:bg-neutral-100">
                                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $count++ }}</td>
                                                    <td class="whitespace-nowrap px-6 py-4">{{ $item->jenis }}</td>
                                                    <td class="whitespace-nowrap px-6 py-4">{{ $item->type }}</td>
                                                    <td class="whitespace-nowrap px-6 py-4">{{ number_format($item->hours_meter, 0, ',', '.') }} jam</td>
                                                    <td class="whitespace-nowrap px-6 py-4">{{ number_format($item->capacity, 0, ',', '.') }} kg</td>
                                                    <td class="whitespace-nowrap px-6 py-4">{{ $item->engine }}</td>
                                                    <td class="whitespace-nowrap px-6 py-4">{{ number_format($item->lifting_height, 0, ',', '.') }} mm ({{ $item->stage }} Stage)</td>
                                                    <td class="whitespace-nowrap px-6 py-4">{{ number_format($item->load_center, 0, ',', '.') }} mm</td>
                                                    <td class="whitespace-nowrap px-6 py-4">
                                                        @if ($item->latest_status == 1)
                                                            Bekerja
                                                        @else
                                                            Perbaikan
                                                        @endif
                                                    </td>

                                                    <td class="whitespace-nowrap px-6 py-4">
                                                        <div class="flex justify-center gap-2">
                                                            <a href="{{ route('dashboard.show', ['id' => $item->id]) }}">
                                                                <button class="border-primary border rounded-md px-3 py-2 hover:bg-primary-light text-primary hover:text-white transition duration-200 ease-in-out">Riwayat Perbaikan</button>
                                                            </a>

                                                            <form action="{{ route('dashboard.delete', ['id' => $item->id]) }}" method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input class="border-red-700 border rounded-md px-3 py-2 hover:bg-red-700 text-red-700 hover:text-white transition duration-200 ease-in-out" type="submit" value="Hapus">
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="pt-3">
                                        {{ $items->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
