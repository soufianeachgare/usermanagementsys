<x-app-layout>
    <div class="container">
        <div class="flex md:flex-row md:justify-between md:items-center p-4 mb-2">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Role Management</h2>
            <div class="flex flex-row gap-4">
                @can('role-create')
                    <a class='inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
                        href="{{ route('roles.create') }}">Add
                        Role</a>
                @endcan
            </div>
        </div>

        <table class="bg-white rounded-lg shadow-md w-full">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left" width="100px">No</th>
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left" width="280px">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach ($roles as $key => $role)
                    @if ($role->id != Auth::user()->roles->first()->id)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ ++$i }}</td>
                            <td class="py-3 px-6">{{ $role->name }}</td>
                            <td class="py-3 px-6 flex space-x-2">
                                <a class="btn btn-info btn-sm" href="{{ route('roles.show', $role->id) }}"><i
                                        class="fa-solid fa-list"></i></a>
                                @can('role-edit')
                                    <a class="btn btn-primary btn-sm" href="{{ route('roles.edit', $role->id) }}"><svg
                                            xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#1f2937">
                                            <path
                                                d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                        </svg></a>
                                @endcan

                                @can('role-delete')
                                    <form method="POST" action="{{ route('roles.destroy', $role->id) }}"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        {!! $roles->links('pagination::bootstrap-5') !!}
    </div>
</x-app-layout>
