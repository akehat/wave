@if(auth()->user()->subscribed('default'))
    <p>You are subscribed to {{ auth()->user()->subscription('default')->stripe_price }}</p>
    <form action="{{ route('cancel') }}" method="post">
        @csrf
        <button type="submit">Cancel Subscription</button>
    </form>
@else
    <h1>Choose a plan</h1>
    @foreach($plans as $plan)
        <div>
            <h2>{{ $plan->name }}</h2>
            <p>{{ $plan->description }}</p>
            <p>Price: {{ $plan->price }}</p>
            <form action="{{ route('subscribe', $plan) }}" method="post">
                @csrf
                <button type="submit">Subscribe</button>
            </form>
        </div>
    @endforeach
@endif