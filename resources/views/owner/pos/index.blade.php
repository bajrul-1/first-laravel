@extends('layouts.owner')

@section('content')
    <div class="container-fluid p-0">
        <livewire:owner.pos-billing :company_slug="$company_slug" :customer_type="$type ?? 'customer'" />
    </div>
@endsection
