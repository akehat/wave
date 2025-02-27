@extends('theme::layouts.app')

@section('content')
    <h1>Admin Subscription Management</h1>

    <!-- Success and Error Messages -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <!-- User List and Subscription Actions -->
    <section class="card">
        <h2>Users</h2>
        <div class="table-container">
            <table class="user-table">
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
                                Active ({{ $user->subscription('default')->stripe_price }})
                            @else
                                Not subscribed
                            @endif
                        </td>
                        <td>
                            @if($user->subscribed('default'))
                                <form action="{{ route('admin.cancel', $user) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn-danger">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </section>

    <!-- Subscribe User -->
    <section class="card">
        <h2>Subscribe User</h2>
        <form action="{{ route('admin.subscribe') }}" method="post">
            @csrf
            <label>User:</label>
            <select name="user_id" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <label>Plan:</label>
            <select name="plan_id" required>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->price }} {{ $plan->currency }})</option>
                @endforeach
            </select>
            <label>Coupon (Optional):</label>
            <select name="coupon_id">
                <option value="">None</option>
                @foreach($coupons as $coupon)
                    <option value="{{ $coupon->id }}">{{ $coupon->name }} ({{ $coupon->percent_off }}% off)</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">Subscribe</button>
        </form>
    </section>

    <!-- Create Plan -->
    <section class="card">
        <h2>Create Plan</h2>
        <form action="{{ route('admin.plans.store') }}" method="post">
            @csrf
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Description:</label>
            <textarea name="description"></textarea>
            <label>Price:</label>
            <input type="number" name="price" step="0.01" min="0" required>
            <label>Currency:</label>
            <input type="text" name="currency" value="usd" size="3" required>
            <label>Interval:</label>
            <select name="interval" required>
                <option value="day">Day</option>
                <option value="week">Week</option>
                <option value="month">Month</option>
                <option value="year">Year</option>
            </select>
            <label>Interval Count:</label>
            <input type="number" name="interval_count" min="1" required>
            <button type="submit" class="btn-primary">Create Plan</button>
        </form>
    </section>

    <!-- Create Coupon -->
    <section class="card">
        <h2>Create Coupon</h2>
        <form action="{{ route('admin.coupons.store') }}" method="post">
            @csrf
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Percent Off:</label>
            <input type="number" name="percent_off" step="0.01" min="0" max="100" required>
            <label>Duration:</label>
            <select name="duration" required>
                <option value="once">Once</option>
                <option value="repeating">Repeating</option>
                <option value="forever">Forever</option>
            </select>
            <label>Duration in Months (if Repeating):</label>
            <input type="number" name="duration_in_months" min="1">
            <button type="submit" class="btn-primary">Create Coupon</button>
        </form>
    </section>

    <!-- Apply Coupon -->
    <section class="card">
        <h2>Apply Coupon to User</h2>
        <form action="{{ route('admin.apply-coupon') }}" method="post">
            @csrf
            <label>User:</label>
            <select name="user_id" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <label>Coupon ID:</label>
            <input type="text" name="coupon_id" required>
            <button type="submit" class="btn-primary">Apply Coupon</button>
        </form>
    </section>

    <!-- Inline CSS for 2025 Styling -->
    <style>
        /* General Styles */
        body {
            background-color: #121212;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        /* Card Layout for Sections */
        .card {
            max-width: max(70%,400px);
            width: max(70%,400px);
            margin-left: auto;
            margin-right: auto;
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }

        .card h2 {
            
            margin-top: 0;
            margin-bottom: 15px;
        }

        /* Table Styling */
        .table-container {
            overflow-x: auto;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th,
        .user-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #333;
        }

        .user-table th {
            background-color: #2a2a2a;
        }

        .user-table tr:hover {
            background-color: #333;
            transition: background-color 0.3s;
        }

        /* Form Styling */
        label {
            display: block;
            margin-bottom: 5px;
            color: #ccc;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Button Styling */
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Alert Styling */
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #28a745;
            color: #fff;
        }

        .alert-error {
            background-color: #dc3545;
            color: #fff;
        }

        /* Animation for Futuristic Feel */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .card {
                padding: 15px;
            }
            h1 {
                font-size: 2rem;
            }
            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
@endsection