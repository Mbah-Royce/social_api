@component('mail::message')
# Introduction

The body of your message.

<table>
    <thead>
        <tr class="bg-light">
            <th class="border-top-0">ID</th>
            <th class="border-top-0">Name</th>
            <th class="border-top-0">Class</th>
            <th class="border-top-0">Instructor</th>
        </tr>
    </thead>
    <tbody id="myTable">
        @foreach ($data as $key => $record)
        <tr>
            <td>
                {{$record['last_name']}}
            </td>
            <td>
                {{$record['first_name']}}
            </td>
            <td>
                {{$record['gender']}}
            </td>
            <td>
                {{$record['email']}}
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
