@extends('layouts.owner')

@section('content')
    <div class="container-fluid p-0">
        <livewire:owner.sales-history :company_slug="$company_slug" />
    </div>
@endsection
