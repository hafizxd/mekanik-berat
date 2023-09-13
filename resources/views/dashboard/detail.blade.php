<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Riwayat Perbaikan') }}
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

                                    <a href="{{ route('dashboard.export', ['id' => $item->id]) }}">
                                        <button class="border-primary border rounded-md px-3 py-2 hover:bg-primary-light text-primary hover:text-white transition duration-200 ease-in-out">Download</button>
                                    </a>

                                    @php $count = 1; @endphp

                                    <table class="min-w-full text-left text-sm font-light">
                                        <thead class="border-b font-medium dark:border-neutral-500">
                                            <tr>
                                                <th scope="col" class="px-6 py-4">#</th>
                                                <th scope="col" class="px-6 py-4">User</th>
                                                <th scope="col" class="px-6 py-4">Tanggal</th>
                                                <th scope="col" class="px-6 py-4">Hours Meter</th>
                                                <th scope="col" class="px-6 py-4">Catatan</th>
                                                <th scope="col" class="px-6 py-4">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reparations as $key => $reparation)
                                                <tr class="border-b transition duration-300 ease-in-out hover:bg-neutral-100">
                                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $count++ }}</td>
                                                    <td class="whitespace-nowrap px-6 py-4">{{ isset($reparation->user) ? $reparation->user->name : '-' }}</td>
                                                    <td class="whitespace-nowrap px-6 py-4">{{ date('d-m-Y', strtotime($reparation->updated_at)) }}</td>
                                                    <td class="whitespace-nowrap px-6 py-4">{{ number_format($reparation->hours_meter, 0, ',', '.') }} jam</td>
                                                    <td class="whitespace-nowrap px-6 py-4">{{ $reparation->note }}</td>
                                                    <td class="whitespace-nowrap px-6 py-4">
                                                        @if ($reparation->status == 1)
                                                            Bekerja
                                                        @else
                                                            Perbaikan
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
