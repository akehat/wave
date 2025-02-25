<form action="{{ route('admin.subscribe', $user) }}" method="post">
    @csrf
    <label for="plan">Plan:</label>
    <select name="plan_id" id="plan">
        @foreach($plans as $plan)
            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
        @endforeach
    </select>
    <label for="coupon">Coupon:</label>
    <select name="coupon_id" id="coupon">
        <option value="">None</option>
        @foreach($coupons as $coupon)
            <option value="{{ $coupon->id }}">{{ $coupon->name }} ({{ $coupon->percent_off }}% off)</option>
        @endforeach
    </select>
    <button type="submit">Subscribe</button>
</form>