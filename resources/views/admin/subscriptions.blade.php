<table>
    <tr>
        <th>User</th>
        <th>Subscription</th>
        <th>Actions</th>
    </tr>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>
                @if($user->subscribed('default'))
                    Active
                @else
                    Not subscribed
                @endif
            </td>
            <td>
                @if($user->subscribed('default'))
                    <form action="{{ route('admin.cancel', $user) }}" method="post">
                        @csrf
                        <button type="submit">Cancel</button>
                    </form>
                @else
                    <a href="{{ route('admin.subscribe.create', $user) }}">Subscribe</a>
                @endif
            </td>
        </tr>
    @endforeach
</table>