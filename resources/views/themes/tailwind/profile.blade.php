@extends('theme::layouts.app')


@section('content')

	<div class="flex flex-col px-8 mx-auto my-6 xl:px-5 lg:flex-row max-w-7xl">

		<div class="flex flex-col items-center justify-center w-full px-10 py-16 mb-8 mr-6 bg-white border rounded-lg lg:mb-0 lg:flex-1 lg:w-1/3 border-gray-150">
			<img src="{{ Voyager::image($user->avatar) }}" class="w-24 h-24 border-4 border-gray-200 rounded-full">
			<h2 class="mt-8 text-2xl font-bold">{{ $user->name }}</h2>
			<p class="my-1 font-medium text-wave-blue">{{ '@' . $user->username }}</p>
			<div class="px-3 py-1 my-2 text-xs font-medium text-white text-gray-600 bg-gray-200 rounded">{{ $user->role->display_name }}</div>
			<p class="max-w-lg mx-auto mt-3 text-base text-center text-gray-500">{{ $user->profile('about') }}</p>
		</div>

			<div class="flex flex-col w-full p-10 overflow-hidden bg-white border rounded-lg lg:w-2/3 border-gray-150 lg:flex-2">
                <h3 class="text-xl font-bold text-gray-700 mb-3">Application Details</h3>
                <p class="text-lg text-gray-600">Welcome, {{ $user->name }}! Here’s a summary of your profile and broker connections:</p>

                <!-- Profile summary section -->
                <div class="mt-5">
                    <h4 class="text-lg font-semibold text-gray-700">Profile Information</h4>
                    <ul class="list-inside mt-2 text-gray-600">
                        <li><strong>Email:</strong> {{ $user->email }}</li>
                        <li><strong>Username:</strong> {{ '@' . $user->username }}</li>
                        <li><strong>Account Created:</strong> {{ $user->created_at->format('M d, Y') }}</li>
                    </ul>
                </div>

                <!-- Broker connections section -->
                <div class="mt-5">
                    <h4 class="text-lg font-semibold text-gray-700">Connected Brokers</h4>
                    @php
                        $brokers=$user->brokers()->get();

                    @endphp
                    <p class="text-gray-600">You are currently connected to {{ count((array)$brokers[0]) }} broker(s) for automated trading and updates:</p>

                    <ul class="list-inside mt-2">
                        @foreach ($brokers as $broker)
                        @php
                            $brokerAttributes = collect($broker->getAttributes())
                                ->except(['confirmed']) // Exclude 'confirmed' attribute
                                ->filter() // Remove null values
                                ->implode(' '); // Join remaining attributes with a space
                        @endphp
                            <li class="my-2 p-3 bg-gray-100 rounded-lg">
                                <strong>{{ $broker->name }}</strong> - {{ $broker->confirmed?"confirmed":"unconfirmed" }}
                                <div class="text-sm text-gray-500">Account ID: {{ $brokerAttributes}}</div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Additional Info -->
                <p class="mt-8 text-lg text-gray-600">If you wish to manage your broker settings or update your profile information, navigate to your page.</p>
            </div>


@endsection
